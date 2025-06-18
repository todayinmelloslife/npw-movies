# NPW Movies

Este repositório contém o projeto **NPW Movies**, um sistema web para avaliação e cadastro de filmes. O objetivo é permitir que usuários registrem perfis, publiquem críticas e gerenciem seus próprios filmes.

## Visão Geral

O sistema foi desenvolvido como estudo de uma arquitetura MVC simples em PHP. O diretório `trabalho/EIVOM` reúne todo o código fonte, dividido em:

- **Controller**: arquivos responsáveis pelo fluxo de requisições (login, cadastro, criação de filmes, avaliações etc.).
- **View**: páginas que exibem a interface ao usuário.
- **19_moviestar**: contém modelos, classes DAO, recursos visuais, scripts e o `index.php` principal do site.

Além disso, há um diagrama do banco de dados em `trabalho/EIVOM/19_moviestar/diagramaMOVIESTAR.pdf`.

## Recursos Principais

- Autenticação de usuários (registro, login e logout);
- Cadastro e edição de filmes, com upload de imagem e trailer;
- Busca de filmes por título e exibição por categoria;
- Sistema de avaliações (reviews) e atribuição de notas;
- Área de perfil do usuário com gerenciamento de informações pessoais.

## Estrutura do Repositório

```
npw-movies/
├── README.md               # Documento atual
└── trabalho/
    └── EIVOM/
        ├── 19_moviestar/   # Arquivos principais do MovieStar
        ├── Controller/     # Controladores das ações
        ├── View/           # Templates e páginas
        └── README.md
```

## Configuração e Execução

1. Clone este repositório em seu ambiente local.
2. Crie um banco de dados MySQL utilizando o diagrama disponível. Ajuste as credenciais em `trabalho/EIVOM/19_moviestar/db.php`.
3. Inicie um servidor PHP dentro do diretório `trabalho/EIVOM`:

   ```bash
   php -S localhost:8000
   ```

4. Acesse `http://localhost:8000/19_moviestar/index.php` em seu navegador.

> **Importante:** o projeto utiliza sessões PHP e requer a extensão PDO para MySQL habilitada.

## Relatório de Desenvolvimento

O sistema foi estruturado de forma a facilitar a manutenção dos componentes:

- **Modelos** definem as entidades principais do sistema (`User`, `Movie`, `Review`) e oferecem métodos utilitários (geração de token, hash de senha, etc.).
- **DAOs** realizam a comunicação com o banco de dados, encapsulando queries e regras de persistência.
- **Views** são responsáveis por renderizar HTML utilizando Bootstrap e Font Awesome.

A autenticação de usuário é mantida por meio de tokens de sessão. O upload de imagens de filmes e usuários gera nomes aleatórios para evitar colisões.

Durante o desenvolvimento foram realizados testes manuais das principais rotas (cadastro, login, criação de filmes, avaliações e edição de perfil). O diagrama do banco de dados serviu de guia para criação das tabelas.

## Contribuição

Pull requests são bem-vindos. Sinta-se à vontade para abrir issues relatando problemas ou sugerindo melhorias.

## Licença

Este projeto está disponibilizado sem licença específica e pode ser utilizado para fins de estudo.

