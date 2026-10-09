<?php
// Definição dos templates prontos

function getTemplates() {
    return [
        'landing' => [
            'name' => 'Landing Page',
            'description' => 'Página de vendas com hero, benefícios e call-to-action',
            'icon' => 'fa-rocket',
            'color' => '#667eea',
            'elements' => [
                [
                    'type' => 'text',
                    'content' => '🚀 Transforme seu Negócio',
                    'x' => 100,
                    'y' => 100,
                    'width' => 600,
                    'height' => 80,
                    'styles' => ['font-size' => '48px', 'font-weight' => 'bold', 'color' => '#333']
                ],
                [
                    'type' => 'text',
                    'content' => 'Solução completa para sua empresa crescer com tecnologia de ponta',
                    'x' => 100,
                    'y' => 200,
                    'width' => 500,
                    'height' => 60,
                    'styles' => ['font-size' => '18px', 'color' => '#666']
                ],
                [
                    'type' => 'button',
                    'content' => 'Começar Agora',
                    'x' => 100,
                    'y' => 280,
                    'width' => 200,
                    'height' => 50,
                    'styles' => []
                ],
                [
                    'type' => 'image',
                    'content' => 'https://placehold.co/400x300/667eea/white?text=Hero+Image',
                    'x' => 650,
                    'y' => 100,
                    'width' => 400,
                    'height' => 300,
                    'styles' => []
                ],
                [
                    'type' => 'divider',
                    'content' => '',
                    'x' => 100,
                    'y' => 450,
                    'width' => 900,
                    'height' => 10,
                    'styles' => []
                ],
                [
                    'type' => 'text',
                    'content' => '✨ Nossos Benefícios',
                    'x' => 100,
                    'y' => 500,
                    'width' => 400,
                    'height' => 50,
                    'styles' => ['font-size' => '32px', 'font-weight' => 'bold']
                ],
                [
                    'type' => 'text',
                    'content' => '⚡ Rápido\n🔒 Seguro\n💰 Econômico',
                    'x' => 100,
                    'y' => 570,
                    'width' => 300,
                    'height' => 150,
                    'styles' => ['font-size' => '16px']
                ]
            ]
        ],
        
        'portfolio' => [
            'name' => 'Portfólio',
            'description' => 'Layout moderno para mostrar seus trabalhos',
            'icon' => 'fa-briefcase',
            'color' => '#764ba2',
            'elements' => [
                [
                    'type' => 'text',
                    'content' => 'Olá, sou [Seu Nome]',
                    'x' => 100,
                    'y' => 100,
                    'width' => 500,
                    'height' => 70,
                    'styles' => ['font-size' => '42px', 'font-weight' => 'bold']
                ],
                [
                    'type' => 'text',
                    'content' => 'Designer & Desenvolvedor apaixonado por criar experiências incríveis',
                    'x' => 100,
                    'y' => 180,
                    'width' => 500,
                    'height' => 60,
                    'styles' => ['font-size' => '18px', 'color' => '#666']
                ],
                [
                    'type' => 'social',
                    'content' => '',
                    'x' => 100,
                    'y' => 260,
                    'width' => 300,
                    'height' => 50,
                    'styles' => [],
                    'facebook' => '#',
                    'instagram' => '#',
                    'linkedin' => '#'
                ],
                [
                    'type' => 'image',
                    'content' => 'https://placehold.co/300x300/764ba2/white?text=Foto',
                    'x' => 700,
                    'y' => 100,
                    'width' => 250,
                    'height' => 250,
                    'styles' => []
                ],
                [
                    'type' => 'divider',
                    'content' => '',
                    'x' => 100,
                    'y' => 400,
                    'width' => 900,
                    'height' => 10,
                    'styles' => []
                ],
                [
                    'type' => 'text',
                    'content' => '🎨 Meus Trabalhos',
                    'x' => 100,
                    'y' => 440,
                    'width' => 400,
                    'height' => 50,
                    'styles' => ['font-size' => '32px', 'font-weight' => 'bold']
                ],
                [
                    'type' => 'image',
                    'content' => 'https://placehold.co/280x200/667eea/white?text=Projeto+1',
                    'x' => 100,
                    'y' => 510,
                    'width' => 280,
                    'height' => 200,
                    'styles' => []
                ],
                [
                    'type' => 'image',
                    'content' => 'https://placehold.co/280x200/764ba2/white?text=Projeto+2',
                    'x' => 410,
                    'y' => 510,
                    'width' => 280,
                    'height' => 200,
                    'styles' => []
                ],
                [
                    'type' => 'image',
                    'content' => 'https://placehold.co/280x200/4a90d9/white?text=Projeto+3',
                    'x' => 720,
                    'y' => 510,
                    'width' => 280,
                    'height' => 200,
                    'styles' => []
                ]
            ]
        ],
        
        'blog' => [
            'name' => 'Blog',
            'description' => 'Layout limpo para artigos e posts',
            'icon' => 'fa-newspaper',
            'color' => '#4a90d9',
            'elements' => [
                [
                    'type' => 'text',
                    'content' => '📝 Meu Blog',
                    'x' => 100,
                    'y' => 80,
                    'width' => 400,
                    'height' => 60,
                    'styles' => ['font-size' => '36px', 'font-weight' => 'bold']
                ],
                [
                    'type' => 'text',
                    'content' => 'Pensamentos, ideias e aprendizados',
                    'x' => 100,
                    'y' => 150,
                    'width' => 400,
                    'height' => 40,
                    'styles' => ['font-size' => '16px', 'color' => '#666']
                ],
                [
                    'type' => 'divider',
                    'content' => '',
                    'x' => 100,
                    'y' => 210,
                    'width' => 900,
                    'height' => 10,
                    'styles' => []
                ],
                [
                    'type' => 'text',
                    'content' => 'Como começar um projeto do zero',
                    'x' => 100,
                    'y' => 240,
                    'width' => 600,
                    'height' => 40,
                    'styles' => ['font-size' => '24px', 'font-weight' => 'bold', 'color' => '#667eea']
                ],
                [
                    'type' => 'text',
                    'content' => '15 de Janeiro, 2026 • 5 min de leitura',
                    'x' => 100,
                    'y' => 285,
                    'width' => 400,
                    'height' => 30,
                    'styles' => ['font-size' => '14px', 'color' => '#999']
                ],
                [
                    'type' => 'text',
                    'content' => 'Neste artigo vamos explorar os primeiros passos para tirar sua ideia do papel...',
                    'x' => 100,
                    'y' => 320,
                    'width' => 600,
                    'height' => 80,
                    'styles' => ['font-size' => '16px', 'color' => '#555']
                ],
                [
                    'type' => 'button',
                    'content' => 'Ler mais →',
                    'x' => 100,
                    'y' => 410,
                    'width' => 150,
                    'height' => 40,
                    'styles' => []
                ]
            ]
        ],
        
        'store' => [
            'name' => 'Loja Virtual',
            'description' => 'Catálogo de produtos com carrinho',
            'icon' => 'fa-shopping-cart',
            'color' => '#27ae60',
            'elements' => [
                [
                    'type' => 'text',
                    'content' => '🛍️ Minha Loja',
                    'x' => 100,
                    'y' => 80,
                    'width' => 400,
                    'height' => 60,
                    'styles' => ['font-size' => '36px', 'font-weight' => 'bold']
                ],
                [
                    'type' => 'button',
                    'content' => '🛒 Carrinho (0)',
                    'x' => 850,
                    'y' => 90,
                    'width' => 150,
                    'height' => 45,
                    'styles' => []
                ],
                [
                    'type' => 'divider',
                    'content' => '',
                    'x' => 100,
                    'y' => 170,
                    'width' => 900,
                    'height' => 10,
                    'styles' => []
                ],
                [
                    'type' => 'text',
                    'content' => '⭐ Produtos em Destaque',
                    'x' => 100,
                    'y' => 200,
                    'width' => 400,
                    'height' => 50,
                    'styles' => ['font-size' => '28px', 'font-weight' => 'bold']
                ],
                [
                    'type' => 'image',
                    'content' => 'https://placehold.co/250x250/27ae60/white?text=Produto+1',
                    'x' => 100,
                    'y' => 270,
                    'width' => 250,
                    'height' => 250,
                    'styles' => []
                ],
                [
                    'type' => 'text',
                    'content' => 'Produto Incrível\nR$ 99,90',
                    'x' => 100,
                    'y' => 530,
                    'width' => 250,
                    'height' => 60,
                    'styles' => ['font-size' => '16px', 'text-align' => 'center']
                ],
                [
                    'type' => 'button',
                    'content' => 'Comprar',
                    'x' => 150,
                    'y' => 600,
                    'width' => 150,
                    'height' => 40,
                    'styles' => []
                ],
                [
                    'type' => 'image',
                    'content' => 'https://placehold.co/250x250/667eea/white?text=Produto+2',
                    'x' => 400,
                    'y' => 270,
                    'width' => 250,
                    'height' => 250,
                    'styles' => []
                ],
                [
                    'type' => 'text',
                    'content' => 'Outro Produto\nR$ 149,90',
                    'x' => 400,
                    'y' => 530,
                    'width' => 250,
                    'height' => 60,
                    'styles' => ['font-size' => '16px', 'text-align' => 'center']
                ],
                [
                    'type' => 'button',
                    'content' => 'Comprar',
                    'x' => 450,
                    'y' => 600,
                    'width' => 150,
                    'height' => 40,
                    'styles' => []
                ]
            ]
        ]
    ];
}