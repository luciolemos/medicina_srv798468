<?php

declare(strict_types=1);

return [
    // brand:start — logo da marca no topo. create-landing.sh remove este bloco e
    // a marca em public/assets/img/medico/ ao gerar landings de outra clínica.
    'brand' => [
        'logo' => [
            'src' => 'assets/img/medico/logo.webp',
            'src_2x' => 'assets/img/medico/logo@2x.webp',
            'dark_src' => 'assets/img/medico/logo-dark.webp',
            'dark_src_2x' => 'assets/img/medico/logo-dark@2x.webp',
            'width' => 137,
            'height' => 44,
        ],
        // Foto do topo (recorte com fundo transparente). Substitui hero.image.
        'hero_image' => [
            'src' => 'assets/img/medico/foto-540.webp',
            'avif_sources' => [
                ['path' => 'assets/img/medico/foto-360.avif', 'width' => 360],
                ['path' => 'assets/img/medico/foto-540.avif', 'width' => 540],
                ['path' => 'assets/img/medico/foto-720.avif', 'width' => 720],
                ['path' => 'assets/img/medico/foto-900.avif', 'width' => 900],
                ['path' => 'assets/img/medico/foto-1080.avif', 'width' => 1080],
                ['path' => 'assets/img/medico/foto-1260.avif', 'width' => 1260],
            ],
            'sources' => [
                ['path' => 'assets/img/medico/foto-360.webp', 'width' => 360],
                ['path' => 'assets/img/medico/foto-540.webp', 'width' => 540],
                ['path' => 'assets/img/medico/foto-720.webp', 'width' => 720],
                ['path' => 'assets/img/medico/foto-900.webp', 'width' => 900],
                ['path' => 'assets/img/medico/foto-1080.webp', 'width' => 1080],
                ['path' => 'assets/img/medico/foto-1260.webp', 'width' => 1260],
            ],
            'sizes' => '(max-width: 575.98px) 92vw, 540px',
            'width' => 1260,
            'height' => 1229,
            'alt' => 'Médica sorridente de jaleco com estetoscópio, de braços cruzados',
            'cutout' => true,
        ],
        // Rosto da mesma médica, para o cartão de prova social do topo.
        'proof_avatar' => [
            'src' => 'assets/img/medico/avatar-56.webp',
            'sources' => [
                ['path' => 'assets/img/medico/avatar-56.webp', 'width' => 56],
                ['path' => 'assets/img/medico/avatar-112.webp', 'width' => 112],
                ['path' => 'assets/img/medico/avatar-168.webp', 'width' => 168],
            ],
            'sizes' => '56px',
            'width' => 56,
            'height' => 56,
        ],
        // Favicon e ícones de atalho (cruz do logo sobre fundo branco).
        'icons' => [
            'ico' => 'assets/img/medico/icons/favicon.ico',
            'png_192' => 'assets/img/medico/icons/icon-192.png',
            'apple_touch' => 'assets/img/medico/icons/apple-touch-icon.png',
            'manifest' => 'assets/img/medico/icons/site.webmanifest',
        ],
        // Logo usado nos dados estruturados (JSON-LD) no lugar de seo.schema.logo.
        'schema_logo' => 'assets/img/medico/logo-600.png',
    ],
    // brand:end
    'seo' => [
        'title' => 'Clínica Médica | Atendimento médico com hora marcada',
        'description' => 'Clínica médica com consultas, check-ups preventivos e acompanhamento de saúde com atendimento humanizado.',
        'site_name' => 'Clínica Médica',
        'locale' => 'pt_BR',
        'type' => 'website',
        'twitter_card' => 'summary_large_image',
        'image' => [
            'src' => 'assets/img/medico/og.jpg',
            'width' => 1200,
            'height' => 630,
            'alt' => 'Médica em consulta com paciente no consultório',
        ],
        'schema' => [
            'type' => 'MedicalClinic',
            'logo' => 'assets/img/medico/mark.svg',
            'price_range' => '$$',
            'area_served' => 'Natal e região',
            'include_services' => true,
            'include_faq' => true,
        ],
    ],
    // Identificação da clínica: endereço, horário e responsável técnico.
    // DADOS FICTÍCIOS (demonstração): substitua pelos dados reais antes de publicar.
    // A Resolução CFM nº 2.336/2023 exige, na publicidade médica, o nome do diretor
    // técnico com o número do CRM (e do RQE, quando se anuncia especialidade).
    'clinic' => [
        'address' => [
            'street' => 'Condomínio Central Park, nº 255',
            'district' => 'Neópolis',
            'city' => 'Natal',
            'region' => 'RN',
            'postal_code' => '',
            'country' => 'BR',
        ],
        'hours' => [
            ['label' => 'Segunda a sexta', 'time' => '8h às 18h', 'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '08:00', 'closes' => '18:00'],
        ],
        'responsible' => [
            'role' => 'Diretora técnica',
            'name' => 'Dra. Ana Exemplo',
            'registry' => 'CRM/RN 00000',
            'rqe' => '',
        ],
    ],
    // Página /privacidade (LGPD): e-mail do encarregado pelos dados.
    'privacy' => [
        'contact_email' => 'privacidade@natalcode.com.br',
        'updated_at' => '2026-11-27',
    ],
    'nav' => [
        'badge' => 'Clínica médica',
        'cta' => 'Agendar',
    ],
    'typography' => [
        'profile' => 'clinical',
    ],
    'hero' => [
        'badge_icon' => 'heart-pulse',
        'badge' => 'Clínica médica com atendimento humanizado',
        'title_parts' => [
            'Cuidado médico',
            'próximo e seguro',
            'para acompanhar sua saúde em cada etapa.',
        ],
        'lead' => 'Consultas clínicas, check-ups preventivos e acompanhamento contínuo com escuta atenta, agenda organizada e orientação clara para o próximo passo do cuidado.',
        'primary_cta' => [
            'label' => 'Agendar consulta',
            'href' => '#form-orcamento',
            'icon' => 'arrow-right-short',
        ],
        'secondary_cta' => [
            'label' => 'Ver atendimentos',
            'href' => '#features',
            'icon' => 'clipboard2-pulse',
        ],
        'trust_items' => [
            ['icon' => 'shield-check', 'label' => 'Sigilo profissional'],
            ['icon' => 'calendar2-check', 'label' => 'Horário agendado'],
            ['icon' => 'person-heart', 'label' => 'Escuta acolhedora'],
        ],
        'proof' => [
            'avatar' => [
                'src' => 'assets/img/shared/avatars/face2_96.webp',
                'sources' => [
                    ['path' => 'assets/img/shared/avatars/face2_96.webp', 'width' => 96],
                    ['path' => 'assets/img/shared/avatars/face2_160.webp', 'width' => 160],
                ],
                'sizes' => '56px',
                'width' => 56,
                'height' => 56,
            ],
            // Mostra nome, cargo e registro de clinic.responsible no lugar do título.
            'show_responsible' => true,
            'title' => 'Atendimento com continuidade',
            'lines' => [
                'Responde pela equipe médica e pelos protocolos da clínica.',
            ],
        ],
        'image' => [
            'src' => 'assets/img/medico/hero-640.webp',
            'sources' => [
                ['path' => 'assets/img/medico/hero-640.webp', 'width' => 640],
                ['path' => 'assets/img/medico/hero-960.webp', 'width' => 960],
                ['path' => 'assets/img/medico/hero-1896.webp', 'width' => 1896],
            ],
            'sizes' => '(max-width: 768px) 92vw, (max-width: 1200px) 44vw, 840px',
            'desktop_media' => '(min-width: 577px)',
            'mobile' => [
                'src' => 'assets/img/medico/hero-mobile-640.webp',
                'sources' => [
                    ['path' => 'assets/img/medico/hero-mobile-640.webp', 'width' => 640],
                ],
                'sizes' => '92vw',
                'media' => '(max-width: 576px)',
                'width' => 640,
                'height' => 800,
            ],
            'alt' => 'Médica em consulta com paciente no consultório',
            'width' => 640,
            'height' => 360,
        ],
        'metrics' => [
            ['kpi' => 'Seg-Sex', 'label' => 'Agenda clínica'],
            ['kpi' => 'Hora', 'label' => 'Marcada'],
            ['kpi' => 'Retorno', 'label' => 'Acompanhado'],
        ],
    ],
    'moments' => [
        'title' => 'Atendimento para diferentes momentos da sua saúde',
        'text' => 'Uma clínica organizada para prevenção, diagnóstico inicial, acompanhamento e orientação médica responsável.',
        'pills' => [
            ['icon' => 'heart-pulse', 'label' => 'Clínica geral'],
            ['icon' => 'clipboard2-check', 'label' => 'Check-up preventivo'],
            ['icon' => 'activity', 'label' => 'Acompanhamento contínuo'],
            ['icon' => 'capsule', 'label' => 'Orientação terapêutica'],
            ['icon' => 'file-medical', 'label' => 'Exames e encaminhamentos'],
            ['icon' => 'calendar2-check', 'label' => 'Consulta com hora marcada'],
        ],
    ],
    'services' => [
        'title' => 'Serviços da clínica',
        'text' => 'Atendimento médico para queixas do dia a dia, prevenção e seguimento de condições que precisam de cuidado regular.',
        'items' => [
            ['icon' => 'heart-pulse', 'title' => 'Consulta clínica', 'text' => 'Avaliação de sintomas, histórico de saúde, exame físico e orientação do plano de cuidado.'],
            ['icon' => 'clipboard2-pulse', 'title' => 'Check-up preventivo', 'text' => 'Revisão de saúde, fatores de risco, solicitação de exames e leitura dos resultados em consulta.'],
            ['icon' => 'activity', 'title' => 'Acompanhamento contínuo', 'text' => 'Seguimento de pressão alta, diabetes, alterações metabólicas e outras condições crônicas.'],
            ['icon' => 'person-heart', 'title' => 'Saúde da família', 'text' => 'Cuidado para adultos e idosos com comunicação clara para pacientes e familiares.'],
            ['icon' => 'capsule', 'title' => 'Revisão de medicações', 'text' => 'Conferência de uso, horários, interações relatadas e adesão ao tratamento prescrito.'],
            ['icon' => 'hospital', 'title' => 'Encaminhamentos', 'text' => 'Orientação para especialistas, exames complementares e retorno com os resultados.'],
        ],
    ],
    'how' => [
        'title' => 'Como funciona o atendimento',
        'text' => 'Um fluxo simples para reduzir espera, organizar informações e deixar claro o próximo passo.',
        'steps' => [
            'Você solicita o agendamento pelo formulário ou WhatsApp',
            'A equipe confirma horário, dados básicos e motivo da consulta',
            'A consulta é realizada com escuta, exame e orientação médica',
            'Exames, receitas e encaminhamentos são organizados quando necessários',
            'O retorno acompanha resultados e evolução do cuidado',
        ],
        'details_title' => 'Diferenciais no atendimento',
        'details_badge' => 'Clínica',
        'details' => [
            'Agenda com confirmação prévia',
            'Orientação clara antes e depois da consulta',
            'Ambiente reservado e acolhedor',
            'Histórico clínico organizado com segurança',
            'Encaminhamento responsável quando necessário',
        ],
    ],
    'structure' => [
        'title' => 'Estrutura pensada para o paciente',
        'text' => 'A experiência de atendimento combina acolhimento, organização e segurança das informações.',
        'cards' => [
            ['icon' => 'door-open', 'title' => 'Recepção organizada', 'text' => 'Chegada orientada, confirmação de dados e suporte para dúvidas antes da consulta.'],
            ['icon' => 'shield-lock', 'title' => 'Privacidade', 'text' => 'Dados de contato e informações clínicas tratados com cuidado e acesso restrito.'],
            ['icon' => 'clipboard2-check', 'title' => 'Plano de cuidado', 'text' => 'Registro das orientações, pedidos de exame e próximos passos para acompanhamento.'],
        ],
    ],
    'location' => [
        'title' => 'Localização',
        'eyebrow' => 'Natal, RN',
        'address' => 'Condomínio Central Park, nº 255 — Neópolis',
        'description' => 'Clínica com agenda organizada para receber cada pessoa com privacidade, pontualidade e orientação clara antes do primeiro atendimento.',
        'details' => [
            ['icon' => 'clock', 'label' => 'Segunda a sexta, das 8h às 18h'],
            ['icon' => 'calendar2-check', 'label' => 'Atendimentos realizados mediante agendamento prévio'],
            ['icon' => 'shield-lock', 'label' => 'Ambiente reservado para conversas sensíveis'],
        ],
        'note' => 'Após o contato, a equipe confirma disponibilidade de horário e envia as orientações de chegada.',
        'primary_label' => 'Solicitar agendamento',
        'secondary_label' => 'Abrir rota',
        'map_title' => 'Localização da clínica',
        'map_subtitle' => 'Condomínio Central Park nº 255, Neópolis, Natal/RN',
        'map_embed_url' => 'https://maps.google.com/maps?center=-5.861295633849962,-35.2124969876455&q=-5.861295633849962,-35.2124969876455&z=16&output=embed',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=-5.861295633849962,-35.2124969876455&zoom=16',
        'iframe_title' => 'Mapa de localização da clínica em Natal, RN',
    ],
    'cta' => [
        'title' => 'Precisa marcar uma consulta?',
        'text' => 'Envie seus dados e a equipe retorna para confirmar o melhor horário disponível.',
        'primary_label' => 'Solicitar agendamento',
        'secondary_label' => 'Falar no WhatsApp',
        'note' => 'Em caso de urgência ou emergência, procure o pronto atendimento mais próximo.',
    ],
    'form' => [
        'title' => 'Solicite seu agendamento',
        'text' => 'Informe seus contatos e o motivo da consulta para receber retorno da equipe.',
        'steps' => ['1. Dados', '2. Consulta'],
        'fields' => [
            'name_label' => 'Nome completo',
            'phone_label' => 'Telefone / WhatsApp',
            'phone_placeholder' => '(84) 99999-9999',
            'email_label' => 'Email',
            'message_label' => 'Motivo da consulta',
            'message_placeholder' => 'Ex.: Gostaria de agendar uma consulta clínica na próxima semana.',
            'optional_summary' => 'Adicionar convênio ou observações (opcional)',
            'optional_label' => 'Convênio / observações',
        ],
        'errors' => [
            'name' => 'Informe seu nome.',
            'phone' => 'Informe um telefone válido para contato.',
            'email' => 'Informe um email válido para retorno.',
            'message' => 'Descreva brevemente sua necessidade.',
        ],
        'buttons' => [
            'previous' => 'Voltar',
            'next' => 'Próximo',
            'submit' => 'Enviar solicitação',
        ],
        'privacy_note' => 'Ao enviar, você autoriza o uso dos dados informados para retorno sobre o agendamento. Os registros operacionais são minimizados e os backups de contato são retidos temporariamente.',
    ],
    'faq' => [
        'title' => 'Dúvidas frequentes',
        'text' => 'Informações essenciais antes de solicitar seu agendamento.',
        'items' => [
            [
                'question' => 'Quais atendimentos a clínica realiza?',
                'answer' => 'A clínica realiza consultas médicas, check-ups preventivos, revisão de exames, acompanhamento de condições crônicas e encaminhamentos quando necessário.',
            ],
            [
                'question' => 'Como confirmo meu horário?',
                'answer' => 'Após o envio do formulário, a equipe entra em contato por WhatsApp ou email para confirmar disponibilidade, horário e orientações de chegada.',
            ],
            [
                'question' => 'Devo levar exames anteriores?',
                'answer' => 'Sim, quando possível. Exames, receitas em uso e relatórios anteriores ajudam o médico a entender seu histórico com mais precisão.',
            ],
            [
                'question' => 'Atende convênio?',
                'answer' => 'Informe seu convênio no campo de observações. A equipe confirma cobertura, disponibilidade e condições de atendimento no retorno.',
            ],
            [
                'question' => 'Este site substitui uma consulta médica?',
                'answer' => 'Não. O site facilita contato e agendamento. Orientações, diagnóstico e tratamento dependem de avaliação médica adequada.',
            ],
        ],
    ],
    'footer' => [
        'label' => 'Clínica Médica',
        'address' => 'Atendimento presencial com horário agendado',
        'meta' => 'Consultas, check-ups e acompanhamento clínico',
        'emergency_note' => 'Atendimento eletivo. Em caso de urgência ou emergência, procure o pronto atendimento mais próximo.',
        'credit' => 'Desenvolvido por <a href="https://natalcode.com.br/" target="_blank" rel="noopener noreferrer">NatalCode</a> - Soluções Digitais',
    ],
    'floating_whatsapp' => [
        'label' => 'WhatsApp',
        'aria_label' => 'Falar no WhatsApp',
    ],
];
