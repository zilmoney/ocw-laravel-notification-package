# Publishing Guide for OnlineCheckWriter Laravel Package

This guide explains how to publish and maintain your package on GitHub and Packagist.

## Current Setup

- **GitHub Repository**: https://github.com/zilmoney/ocw-laravel-notification-package.git
- **Package Name**: `laravel-notification-channels/onlinecheckwriter`
- **Current Version**: 1.0.0

## Step 1: Push Changes to GitHub

### Initial Push (if not done already)
```bash
# Add all files
git add .

# Commit changes
git commit -m "Initial release v1.0.0"

# Push to GitHub
git push origin main
```

### Regular Updates
```bash
# Stage your changes
git add .

# Commit with descriptive message
git commit -m "Description of changes"

# Push to GitHub
git push origin main
```

## Step 2: Create Version Tags

Composer uses git tags for versioning. Create tags for each release:

### Create and Push a Tag
```bash
# Create an annotated tag (recommended)
git tag -a v1.0.0 -m "Release version 1.0.0"

# Push the tag to GitHub
git push origin v1.0.0

# Or push all tags at once
git push origin --tags
```

### Tag Naming Convention
- Use semantic versioning: `v1.0.0`, `v1.0.1`, `v1.1.0`, `v2.0.0`
- Format: `vMAJOR.MINOR.PATCH`
- Always use the `v` prefix

### List Existing Tags
```bash
git tag -l
```

### Delete a Tag (if needed)
```bash
# Delete locally
git tag -d v1.0.0

# Delete from GitHub
git push origin --delete v1.0.0
```

## Step 3: Publish to Packagist

Packagist is the main repository for PHP packages. Users can install your package via Composer once it's published.

### Prerequisites
1. Create an account at https://packagist.org
2. Verify your GitHub account in Packagist settings

### Submit Your Package
1. Go to https://packagist.org/packages/submit
2. Enter your repository URL: `https://github.com/zilmoney/ocw-laravel-notification-package`
3. Click "Check" - Packagist will analyze your package
4. Click "Submit" to publish

### Auto-Update Setup (Recommended)
1. Go to your package page on Packagist
2. Click "Settings"
3. Enable "GitHub Service Hook" or "Update by Webhook"
4. Add the webhook URL to your GitHub repository:
   - Go to GitHub repo → Settings → Webhooks → Add webhook
   - Payload URL: Your Packagist webhook URL
   - Content type: `application/json`
   - Events: Select "Just the push event"

This ensures Packagist automatically updates when you push new tags.

## Step 4: Release Workflow

### Standard Release Process

1. **Update Version in Code** (if needed):
   ```bash
   # Update CHANGELOG.md or README.md with version info
   ```

2. **Commit Changes**:
   ```bash
   git add .
   git commit -m "Prepare for v1.0.1 release"
   git push origin main
   ```

3. **Create and Push Tag**:
   ```bash
   git tag -a v1.0.1 -m "Release v1.0.1: Bug fixes and improvements"
   git push origin v1.0.1
   ```

4. **Create GitHub Release** (Optional but recommended):
   - Go to GitHub repository → Releases → "Create a new release"
   - Choose the tag you just created
   - Add release notes
   - Click "Publish release"

5. **Packagist Auto-Update**:
   - If webhook is configured, Packagist will update automatically
   - Otherwise, manually trigger update on Packagist

## Step 5: Users Can Now Install

Once published to Packagist, users can install your package:

```bash
composer require laravel-notification-channels/onlinecheckwriter
```

## Best Practices

### Versioning Strategy
- **MAJOR** (v2.0.0): Breaking changes
- **MINOR** (v1.1.0): New features, backward compatible
- **PATCH** (v1.0.1): Bug fixes, backward compatible

### Commit Messages
- Use clear, descriptive commit messages
- Follow conventional commits if possible: `feat:`, `fix:`, `docs:`, etc.

### Testing Before Release
```bash
# Run tests
vendor/bin/phpunit

# Check for issues
composer validate
```

### Branch Strategy
- `main` branch: Stable releases
- `develop` branch: Development (optional)
- Feature branches: For new features

## Troubleshooting

### Packagist Not Updating
- Check webhook configuration
- Manually trigger update on Packagist
- Verify tag exists on GitHub

### Version Not Showing
- Ensure tag follows semantic versioning
- Check that tag is pushed to GitHub
- Wait a few minutes for Packagist to sync

### Composer Install Fails
- Verify package name matches exactly: `laravel-notification-channels/onlinecheckwriter`
- Check minimum stability in user's composer.json
- Ensure all dependencies are available

## Quick Reference Commands

```bash
# Check current status
git status

# View remote repository
git remote -v

# Create and push tag
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0

# View all tags
git tag -l

# Push all changes and tags
git push origin main --tags
```

