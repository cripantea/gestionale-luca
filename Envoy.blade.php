
@setup
$server = 'srv961648.hstgr.cloud';

$baseDir = '/var/www/html/dashboard';
$user = 'cristi';

$php = 'php';
$userAndServer = $user . '@'. $server;
$repository = "/cripantea/gestionale-luca.git";
$npm = 'npm';

$branch = $branch ?? 'main';

# naming convention
$releasesDir = "{$baseDir}/releases";
$persistentDir = "{$baseDir}/persistent";
$currentDir = "{$baseDir}/current";
$newReleaseName = date('Ymd-His');
$newReleaseDir = "{$releasesDir}/{$newReleaseName}";


function logMessage($message) {
return "echo '\033[32m" .$message. "\033[0m';\n";
}
@endsetup

@servers(['local' => '127.0.0.1', 'remote' => $userAndServer])

@story('deploy')
cloneRepository
runComposer
runNpm
generateAssets
updateSymlinks
optimizeInstallation
migrateDatabase
blessNewRelease
cleanOldReleases
finishDeploy
@endstory

@task('testaa', ['on' => 'remote'])
{{-- whoami --}}
whoami
pwd

{{ $npm }}-v
@endtask

@task('checkAssets', ['on' => 'remote'])
{{ logMessage('🔍  Checking assets…') }}
echo "Current release directory:"
ls -la {{ $currentDir }}
echo ""
echo "Public build directory contents:"
ls -laR {{ $currentDir }}/public/build/ || echo "❌ Build directory not found"
echo ""
echo "Manifest file:"
cat {{ $currentDir }}/public/build/manifest.json || echo "❌ Manifest not found"
echo ""
echo "Permissions:"
ls -ld {{ $currentDir }}/public/build
echo ""
echo "Node and NPM versions:"
node -v
npm -v
echo ""
echo "Check if .vite directory exists:"
ls -la {{ $currentDir }}/public/build/.vite/ || echo "No .vite directory"
@endtask

@task('rebuildAssets', ['on' => 'remote'])
{{ logMessage('🔨  Rebuilding assets on current release…') }}
cd {{ $currentDir }}
echo "Cleaning old build..."
rm -rf public/build/*
echo "Running npm install..."
npm install --production=false
echo "Running build with verbose output..."
npm run build
echo "Checking result..."
ls -la public/build/
[ -f public/build/manifest.json ] && echo "✅ Build SUCCESS" || echo "❌ Build FAILED"
@endtask

@task('cloneRepository', ['on' => 'remote'])
{{ logMessage('🌀  Cloning repository…') }}
[ -d {{ $releasesDir }} ] || mkdir {{ $releasesDir }};
[ -d {{ $persistentDir }} ] || mkdir {{ $persistentDir }};
[ -d {{ $persistentDir }}/uploads ] || mkdir {{ $persistentDir }}/uploads;
[ -d {{ $persistentDir }}/storage ] || mkdir {{ $persistentDir }}/storage;
[ -d {{ $persistentDir }}/storage/framework ] || mkdir {{ $persistentDir }}/storage/framework;
[ -d {{ $persistentDir }}/storage/framework/cache ] || mkdir {{ $persistentDir }}/storage/framework/cache;
[ -d {{ $persistentDir }}/storage/framework/sessions ] || mkdir {{ $persistentDir }}/storage/framework/sessions;
[ -d {{ $persistentDir }}/storage/framework/views ] || mkdir {{ $persistentDir }}/storage/framework/views;

cd {{ $releasesDir }};

# Create the release dir
mkdir {{ $newReleaseDir }};


# Clone the repo
git clone --depth 1 --branch {{ $branch }} git@github.com:{{ $repository }} {{ $newReleaseName }}

# Configure sparse checkout
cd {{ $newReleaseDir }}
git config core.sparsecheckout true
echo "*" > .git/info/sparse-checkout
echo "!storage" >> .git/info/sparse-checkout
echo "!public/build" >> .git/info/sparse-checkout
git read-tree -mu HEAD

# Mark release
cd {{ $newReleaseDir }}
echo "{{ $newReleaseName }}" > public/release-name.txt
@endtask

@task('runComposer', ['on' => 'remote'])
cd {{ $newReleaseDir }};
{{ logMessage('🚚  Running Composer…') }}
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader;
@endtask

@task('runNpm', ['on' => 'remote'])
{{ logMessage('📦  Running Npm…') }}
cd {{ $newReleaseDir }};
# Install ALL dependencies (including devDependencies needed for build)
npm ci --include=dev || npm install --include=dev
echo "✅ NPM dependencies installed"
@endtask

@task('generateAssets', ['on' => 'remote'])
{{ logMessage('🌅  Generating assets…') }}
cd {{ $newReleaseDir }};
echo "Current directory: $(pwd)"
echo "Node version: $(node -v)"
echo "NPM version: $(npm -v)"
echo "Running npm run build..."
npm run build 2>&1 | tee build.log
BUILD_EXIT_CODE=${PIPESTATUS[0]}
echo "Build exit code: $BUILD_EXIT_CODE"
if [ $BUILD_EXIT_CODE -ne 0 ]; then
    echo "❌ Build failed! Check build.log"
    cat build.log
    exit 1
fi
echo "Checking if manifest exists..."
if [ ! -f {{ $newReleaseDir }}/public/build/manifest.json ]; then
    echo "❌ Manifest not generated!"
    echo "Build directory contents:"
    ls -laR {{ $newReleaseDir }}/public/build/
    exit 1
fi
echo "✅ Build completed successfully"
# Fix permissions for build directory
chmod -R 755 {{ $newReleaseDir }}/public/build
chown -R {{ $user }}:{{ $user }} {{ $newReleaseDir }}/public/build
@endtask

@task('updateSymlinks', ['on' => 'remote'])
{{ logMessage('🔗  Updating symlinks to persistent data…') }}
# Remove the storage directory and replace with persistent data
rm -rf {{ $newReleaseDir }}/storage;
cd {{ $newReleaseDir }};
ln -nfs {{ $baseDir }}/persistent/storage storage;

# Import the environment config
cd {{ $newReleaseDir }};
ln -nfs {{ $baseDir }}/.env .env;

# Symlink the persistent fonts to the public directory
#cd {{ $baseDir }}/persistent/fonts
#git pull origin master
#ln -nfs {{ $baseDir }}/persistent/fonts {{ $newReleaseDir }}/public/fonts;
@endtask

@task('optimizeInstallation', ['on' => 'remote'])
{{ logMessage('✨  Optimizing installation…') }}
cd {{ $newReleaseDir }};
{{ $php }} artisan clear-compiled;
@endtask

@task('backupDatabase', ['on' => 'remote'])
{{-- {{ logMessage('📀  Backing up database…') }} --}}
{{-- cd {{ $newReleaseDir }} --}}
{{--  $php }} artisan backup:run --}}
@endtask

@task('migrateDatabase', ['on' => 'remote'])
{{ logMessage('🙈  Migrating database…') }}
cd {{ $newReleaseDir }};
{{ $php }} artisan migrate --force;
@endtask

@task('blessNewRelease', ['on' => 'remote'])
{{ logMessage('🙏  Blessing new release…') }}
ln -nfs {{ $newReleaseDir }} {{ $currentDir }};
cd {{ $newReleaseDir }}
# Create symlink for Vite 7 manifest compatibility (if needed)
if [ -f {{ $newReleaseDir }}/public/build/.vite/manifest.json ] && [ ! -f {{ $newReleaseDir }}/public/build/manifest.json ]; then
    cd {{ $newReleaseDir }}/public/build
    ln -s .vite/manifest.json manifest.json
    echo "✅ Created manifest symlink for Vite 7 compatibility"
fi
cd {{ $newReleaseDir }}
{{ $php }} artisan optimize:clear
{{ $php }} artisan config:cache
{{ $php }} artisan storage:link
{{ $php }} artisan queue:restart
# Ensure public directory has correct permissions
chmod -R 755 {{ $newReleaseDir }}/public
# Verify build assets exist
[ -f {{ $newReleaseDir }}/public/build/.vite/manifest.json ] && echo "✅ Vite 7 manifest found" || echo "❌ Manifest NOT found"
@endtask

@task('cleanOldReleases', ['on' => 'remote'])
{{ logMessage('🚾  Cleaning up old releases…') }}
# Delete all but the 5 most recent.
cd {{ $releasesDir }}
ls -dt {{ $releasesDir }}/* | tail -n +6 | xargs -d "\n" chown -R {{ $user }} .;
ls -dt {{ $releasesDir }}/* | tail -n +6 | xargs -d "\n" rm -rf;
@endtask

@task('finishDeploy', ['on' => 'local'])
{{ logMessage('🚀  Application deployed!') }}
@endtask

@task('deployOnlyCode', ['on' => 'remote'])
{{ logMessage('💻  Deploying code changes…') }}
cd {{ $currentDir }}
git pull origin {{ $branch }}
{{ $php }} artisan config:clear
{{ $php }} artisan view:clear
{{ $php }} artisan schedule:clear-cache
{{ $php }} artisan queue:restart
{{ $php }} artisan config:cache
echo "" | sudo -S /usr/sbin/service php8.3-fpm reload
@endtask
