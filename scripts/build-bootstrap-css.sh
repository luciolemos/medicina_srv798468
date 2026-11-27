#!/usr/bin/env bash
set -euo pipefail

# Gera public/assets/vendor/bootstrap/bootstrap.purged.min.css: o Bootstrap só com as
# regras usadas pelos templates/JS/conteúdo (PurgeCSS). O CSS completo tem ~228 KiB e
# bloqueia a renderização; o reduzido tem uma fração disso.
#
#   bash scripts/build-bootstrap-css.sh          # gera o arquivo
#   bash scripts/build-bootstrap-css.sh --check  # falha se o arquivo estiver desatualizado
#
# Classes adicionadas em tempo de execução (JS do Bootstrap, landing.js ou montadas no
# Twig) precisam estar na safelist abaixo.

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_ROOT"

PURGECSS_VERSION="9.1.0"
SOURCE="public/assets/vendor/bootstrap/bootstrap.min.css"
TARGET="public/assets/vendor/bootstrap/bootstrap.purged.min.css"

TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT

cat > "$TMP_DIR/purgecss.config.cjs" <<'EOF'
module.exports = {
  content: [
    "views/**/*.twig",
    "public/assets/js/**/*.js",
    "config/content/**/*.php",
    "src/**/*.php",
  ],
  safelist: {
    standard: [
      // Collapse do Bootstrap (navbar) e estados alternados pelo landing.js
      "show", "showing", "hiding", "collapse", "collapsing", "collapsed",
      "active", "disabled", "d-none", "fade",
      // Validação de formulário
      "is-invalid", "is-valid", "was-validated", "invalid-feedback", "valid-feedback",
      // alert-{{ alert_class }} em home.twig
      /^alert-/,
    ],
  },
  // Mantém variáveis CSS (--bs-*) e keyframes, usados por landing.css.
  variables: false,
  keyframes: false,
  fontFace: false,
};
EOF

npx -y "purgecss@${PURGECSS_VERSION}" \
  --config "$TMP_DIR/purgecss.config.cjs" \
  --css "$SOURCE" \
  --output "$TMP_DIR/" >/dev/null

GENERATED="$TMP_DIR/$(basename "$SOURCE")"
# Remove a referência ao sourcemap (o .map não é publicado).
sed -i '/sourceMappingURL/d' "$GENERATED"

if [[ "${1:-}" == "--check" ]]; then
  if ! cmp -s "$GENERATED" "$TARGET"; then
    echo "[error] $TARGET está desatualizado. Rode: bash scripts/build-bootstrap-css.sh" >&2
    exit 1
  fi
  echo "[ok  ] bootstrap reduzido atualizado."
  exit 0
fi

cp "$GENERATED" "$TARGET"
echo "[ok  ] $TARGET ($(( $(wc -c < "$TARGET") / 1024 )) KiB, original $(( $(wc -c < "$SOURCE") / 1024 )) KiB)"
