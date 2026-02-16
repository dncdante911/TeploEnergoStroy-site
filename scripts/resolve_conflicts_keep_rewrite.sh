#!/usr/bin/env bash
set -euo pipefail

# Resolves PR conflicts by keeping this branch's version (ours)
# for all conflicted files after merging origin/main.
# Use when this branch is a full rewrite and should dominate conflict resolution.

TARGET_MAIN_REF="${1:-origin/main}"

if ! git rev-parse --git-dir >/dev/null 2>&1; then
  echo "Not a git repository" >&2
  exit 1
fi

CURRENT_BRANCH="$(git rev-parse --abbrev-ref HEAD)"

echo "Current branch: ${CURRENT_BRANCH}"
echo "Fetching remotes..."
git fetch --all --prune

echo "Starting merge with ${TARGET_MAIN_REF} (no commit)..."
set +e
git merge --no-ff --no-commit "${TARGET_MAIN_REF}"
MERGE_EXIT=$?
set -e

if [[ ${MERGE_EXIT} -eq 0 ]]; then
  echo "Merge completed without conflicts."
  git commit -m "Merge ${TARGET_MAIN_REF} into ${CURRENT_BRANCH}"
  echo "Done."
  exit 0
fi

CONFLICTED_FILES="$(git diff --name-only --diff-filter=U)"
if [[ -z "${CONFLICTED_FILES}" ]]; then
  echo "Merge failed, but no conflicted files were detected." >&2
  exit 2
fi

echo "Conflicts detected. Keeping OURS for the following files:"
echo "${CONFLICTED_FILES}"

# Keep branch versions for all conflicted files
while IFS= read -r file; do
  [[ -z "$file" ]] && continue
  git checkout --ours -- "$file"
  git add -- "$file"
done <<< "${CONFLICTED_FILES}"

git commit -m "Resolve merge conflicts with ${TARGET_MAIN_REF} by keeping rewrite branch versions"

echo "Conflict resolution commit created."
echo "Review with: git show --name-only --oneline HEAD"
