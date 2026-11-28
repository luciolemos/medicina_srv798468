# Landing Médica

Protótipo de landing page para serviços de saúde, pronto para derivar projetos como `/medico`, `/pediatria`, `/veterinaria`, `/odontologia` e outros subdiretórios.

## Requisitos

- PHP 8.3 ou 8.4
- Composer
- Node.js para testes E2E (Playwright), Lighthouse e para regenerar o Bootstrap reduzido (PurgeCSS via `npx`)

## Configuração

Copie `.env.example` para `.env` e ajuste:

- `APP_NAME`: nome público da clínica.
- `APP_PAGE_TITLE`: título da página.
- `APP_SLUG`: identificador curto da landing, por exemplo `medico`, `pediatria` ou `odontologia`.
- `APP_REQUEST_PREFIX`: prefixo dos protocolos, por exemplo `MED`, `PED`, `ODO`.
- `APP_CONTENT_FILE`: arquivo de conteúdo em `config/content/` sem a extensão `.php`; use `landing` para uma landing por repositório.
- `APP_CANONICAL_URL`: URL pública canônica da landing, com domínio e subcaminho; quando vazio, o app deriva do host da requisição.
- `APP_BASE`: subcaminho de publicação, por exemplo `/medico`, `/pediatria` ou `/odontologia`.
- `APP_PALETTE`: paleta padrão da landing (`blue`, `red`, `emerald`, `amber` ou `violet`).
- `APP_SHOW_PALETTE_SELECTOR`: use `true` em catálogo/demo para mostrar o seletor de cores; mantenha `false` na landing final.
- `FACEBOOK_URL`: link oficial do Facebook.
- `APP_WHATSAPP_NUMBER` e `APP_WHATSAPP_MESSAGE`: número e mensagem inicial usados para gerar o link oficial de WhatsApp.
- `WHATSAPP_URL`: fallback legado de WhatsApp quando `APP_WHATSAPP_NUMBER` estiver vazio.
- `CONTACT_TO` e `CONTACT_FROM`: emails usados pelo formulário.
- Configurações `SMTP_*`, se `MAIL_DRIVER="smtp"`.
- `LEAD_LOG_RETENTION_DAYS`: retenção dos logs de leads e fallback em `storage/`.
- `LEAD_LOG_HASH_SALT`: sal usado para pseudonimizar IP, user-agent e hashes operacionais; defina um valor privado no `.env` real.
- `RECAPTCHA_ENABLED`: mantenha `false` em homologação ou domínios ainda não cadastrados no Google; use `true` apenas no `.env` real de produção.
- `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY`, `RECAPTCHA_MIN_SCORE`, `RECAPTCHA_ALLOWED_HOSTNAME` e `RECAPTCHA_ACTION`: configuram o reCAPTCHA v3 do formulário; mantenha os segredos somente no `.env` não versionado da produção.

O arquivo `.env` não é versionado. Não coloque chaves secretas de SMTP/reCAPTCHA em `.env.example`, README ou arquivos de backup versionados.

## Execução Local

```bash
composer install
bash scripts/dev-local.sh
```

Abra `http://127.0.0.1:8000/`.

## Estrutura do código

- `public/index.php`: ponto de entrada. Carrega o `.env`, abre a sessão e o autoload, e roda o app.
- `config/app.php`: monta o app (conteúdo, Twig e globais, reCAPTCHA, rate limit, e-mail, log de leads, controller, middlewares e rotas). É aqui que se liga um serviço novo.
- `routes/web.php`: rotas (`/`, `/privacidade`, envio do formulário).
- `src/`: controller, serviços do formulário (`Contact/`), SEO e conteúdo (`Core/`) e middleware de cabeçalhos de segurança.
- `views/`: templates Twig. `config/content/`: textos de cada nicho.

## Assets de CSS reduzidos

A página não carrega o Bootstrap nem o Bootstrap Icons completos, e sim versões reduzidas geradas a partir do que os templates usam. Isso mantém o LCP dentro do perfil `strict` do Lighthouse.

| Arquivo carregado | Gerado por | Quando rodar |
|---|---|---|
| `public/assets/vendor/bootstrap-icons/bootstrap-icons.subset.css` | `php scripts/build-icons-css.php` | ao usar um ícone `bi-*` novo em template, JS ou conteúdo |
| `public/assets/vendor/bootstrap/bootstrap.purged.min.css` | `bash scripts/build-bootstrap-css.sh` | ao usar uma classe do Bootstrap que ainda não aparecia no projeto |

Os dois arquivos gerados são versionados. O `quality-gate` roda `build-icons-css.php --check` e falha se o subconjunto de ícones estiver desatualizado. Classes que o JavaScript adiciona em tempo de execução precisam estar na *safelist* de `build-bootstrap-css.sh`.

`scripts/create-landing.sh` regenera o subconjunto de ícones na cópia nova.

## Logo e foto da marca

Os originais ficam em `resources/brand/` (fora de `public/`, não são publicados). As versões servidas ficam em `public/assets/img/brand/` e são ligadas no bloco `brand` de `config/content/landing.php`. Para regenerá-las após trocar um original (ImageMagick com suporte a AVIF):

```bash
# Foto do topo: 6 larguras em AVIF e WebP
convert resources/brand/hero.png -trim +repage /tmp/hero.png
for w in 360 540 720 900 1080 1260; do
  convert /tmp/hero.png -filter Lanczos -resize ${w}x -strip -quality 80 -define webp:alpha-quality=85 public/assets/img/brand/hero-$w.webp
  convert /tmp/hero.png -filter Lanczos -resize ${w}x -strip -quality 55 public/assets/img/brand/hero-$w.avif
done
```

O logo segue o mesmo processo em 44 px e 88 px de altura (`medicare-logo.webp` e `@2x`). A versão `-dark` troca os pixels verdes por `#e3efe7` para aparecer no tema escuro. Nichos não herdam o bloco `brand`, e o `create-landing.sh` remove o bloco, `public/assets/img/brand/` e `resources/brand/` ao gerar outra clínica.

## Criando uma nova landing

1. Copie este projeto para o novo diretório, por exemplo `/var/www/pediatria`.
2. Remova a identidade Git herdada se o destino for outro repositório.
3. Ajuste `.env`: `APP_BASE`, nome público, links sociais, WhatsApp, SMTP e reCAPTCHA.
4. Troque textos no arquivo ativo de `config/content/` e imagens em `public/assets/img/hero/` e `public/assets/img/social/`.
5. Rode `composer test` e `bash scripts/run-tests.sh --url "http://127.0.0.1:8000/"`.

Também há um gerador para criar uma cópia limpa do protótipo:

```bash
bash scripts/create-landing.sh --list-presets
bash scripts/create-landing.sh pediatria --name "Clínica Pediátrica" --mark P --palette emerald --request-prefix PED
```

Quando existir um arquivo de nicho com o mesmo slug, como `config/content/pediatria.php`, o gerador define `APP_CONTENT_FILE="pediatria"` automaticamente, mantém os assets específicos desse nicho e remove variações de nichos não usados na cópia final.

## Testes

```bash
composer test          # PHPUnit
composer analyse       # PHPStan (nível 5, ver phpstan.neon)
npx playwright test    # E2E
bash scripts/quality-gate.sh   # tudo o que o CI roda antes dos testes de navegador
```

Os scripts em `scripts/` também mantêm smoke tests de paleta, formulário e frontend para ambientes publicados.

Valide conteúdo, SEO e assets antes de publicar:

```bash
php scripts/validate-landing-content.php
php scripts/validate-landing-content.php --content pediatria --slug pediatria
php scripts/validate-landing-content.php --content odontologia --slug odontologia
php scripts/validate-landing-content.php --content veterinaria --slug veterinaria
bash scripts/audit-generated-landings.sh
```

`scripts/audit-generated-landings.sh` cria cópias temporárias para os presets, confere `.env`, conteúdo ativo, assets obrigatórios e ausência de arquivos herdados de outros nichos. O `quality-gate` roda essa auditoria automaticamente.

## Privacidade e retenção

`lead-events.log` registra eventos operacionais sem nome, telefone, email, mensagem, IP ou user-agent em texto claro. Quando o e-mail falha, `contatos-fallback.log` guarda o contato completo para recuperação manual e deve ser retido por pouco tempo.

Limpeza manual ou por cron:

```bash
php scripts/prune-lead-data.php --days 30
php scripts/prune-lead-data.php --days 30 --dry-run
```

## Conteúdo

O conteúdo principal está em:

- `config/content/landing.php`: inclui os blocos `clinic` (endereço, horários, responsável técnico com registro no conselho, exigido pela Resolução CFM nº 2.336/2023) e `privacy` (e-mail do encarregado), que alimentam o rodapé, o JSON-LD e a página `/privacidade`. Os valores de exemplo (`Exemplo`, `00000`, `@example.com`) aparecem como `[info]` no `validate-landing-content.php` até serem trocados pelos reais.
- `views/partials/navbar.twig`
- `views/partials/footer.twig`
- `public/assets/img/`

Para protótipos com mais de uma variação no mesmo repositório, crie outro arquivo em `config/content/`, por exemplo `config/content/pediatria.php`, e aponte `APP_CONTENT_FILE="pediatria"`. Quando `APP_CONTENT_FILE` não é informado, o app tenta `APP_SLUG` e depois volta para `landing`.

A seção `seo` em `config/content/landing.php` controla título, descrição, Open Graph, Twitter Card e JSON-LD. Para novos nichos, ajuste principalmente `seo.schema.type`, por exemplo `MedicalClinic`, `Dentist` ou `VeterinaryCare`, além de imagem social, área atendida e serviços.

Use `typography.profile` para diferenciar a personalidade visual de cada landing sem alterar o layout. Perfis disponíveis: `clinical` para clínica médica, `family` para pediatria/família, `premium` para estética ou odontologia de alto padrão, `warm` para veterinária ou atendimento acolhedor e `technical` para páginas mais objetivas. O perfil troca famílias tipográficas, pesos e ritmo dos títulos via CSS variables.

Os presets recomendados para cada nicho ficam em `config/presets/niches.php`. O gerador `scripts/create-landing.sh` usa esses presets para sugerir nome, paleta, tipografia, schema SEO e prefixo de protocolo quando o slug é conhecido.

Os conteúdos de nicho versionados são `config/content/pediatria.php`, `config/content/odontologia.php`, `config/content/veterinaria.php`, `config/content/estetica.php` e `config/content/psicologia.php`. Eles herdam a estrutura base de `landing.php` e sobrescrevem textos, SEO, tipografia, serviços, FAQ e mensagens de formulário para cada área.

As imagens principais seguem nomes padronizados: `public/assets/img/hero/{slug}-640.webp`, `{slug}-960.webp`, `{slug}-1896.webp`, `{slug}-mobile-640.webp`, `public/assets/img/social/{slug}-og.jpg` e `public/assets/img/{slug}-mark.svg`. O corte mobile é vertical para preservar o rosto/atendimento em telas estreitas. O gerador renomeia os placeholders para o slug novo; depois substitua esses arquivos por imagens finais do nicho.

O Twig roda com `auto_reload`, então templates alterados por deploy são recompilados sozinhos; não é preciso limpar `storage/cache/twig`.
