#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
DIST="$ROOT/dist"
STAGE="$(mktemp -d)"
PACKAGE="$STAGE/faq"
ARCHIVE="$DIST/faq_1.3.0.zip"

cleanup() {
    rm -rf "$STAGE"
}
trap cleanup EXIT

rm -rf "$DIST"
mkdir -p "$DIST" "$PACKAGE"

while IFS= read -r -d '' entry; do
    name="$(basename "$entry")"
    case "$name" in
        .*|dist|build-dist.sh)
            continue
            ;;
    esac
    cp -a "$entry" "$PACKAGE/"
done < <(find "$ROOT" -mindepth 1 -maxdepth 1 -print0)

if find "$PACKAGE" -name '.*' -print -quit | grep -q .; then
    echo "Refusing to build: hidden file or directory detected in package."
    find "$PACKAGE" -name '.*' -print
    exit 1
fi

if command -v git >/dev/null 2>&1 && git -C "$ROOT" rev-parse --is-inside-work-tree >/dev/null 2>&1; then
    source_epoch="$(git -C "$ROOT" log -1 --format=%ct)"
    find "$PACKAGE" -exec touch -d "@$source_epoch" {} +
fi

(
    cd "$STAGE"
    zip -X -q -r "$ARCHIVE" faq
)

if zipinfo -1 "$ARCHIVE" | grep -E '(^|/)\.[^/]*($|/)' >/dev/null; then
    echo "Refusing archive: a file or directory beginning with '.' is present."
    zipinfo -1 "$ARCHIVE" | grep -E '(^|/)\.[^/]*($|/)'
    rm -f "$ARCHIVE"
    exit 1
fi

echo "Created $ARCHIVE"
zipinfo -1 "$ARCHIVE"
