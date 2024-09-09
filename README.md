# Projeto para Integração com APIs de Filmes - PHP e Laravel

Este projeto Laravel é uma aplicação web desenvolvida para permitir que os usuários pesquisem e interajam com títulos de filmes, integrando APIs externas como **The Movie DB**. O objetivo é possibilitar a pesquisa de filmes, marcação de status (Assistido, Favorito, Pretende Assistir) e exibição de detalhes dos filmes, utilizando boas práticas de design e arquitetura como **SOLID**, **Clean Architecture**, **Repositories**, **DTOs**, e **Adapters**.

O sistema foi desenhado para ser flexível, possibilitando a adição de múltiplos provedores de API de filmes. A arquitetura implementada segue o padrão **Ports and Adapters (Hexagonal Architecture)**, garantindo que a lógica de negócios permaneça desacoplada dos provedores externos.

## Estrutura do Projeto

### Camadas e Diretórios

Dentro do diretório `app/`, o projeto está organizado da seguinte forma:

- **Domain**:
  - **Entities**: Representa as entidades de domínio, como o `Movie` e `User`, que contêm apenas dados relevantes ao negócio e são agnósticas a frameworks.
  - **Repositories**: Define interfaces para os repositórios, aplicando o princípio da inversão de dependência (SOLID).
  - **UseCases**: Contém a lógica de negócios (como a criação de status de filmes, obtenção de filmes por usuário, etc.) e é responsável por orquestrar as ações do sistema.
  
- **Infrastructure**:
  - **Persistence**:
    - **Repositories**: Implementações dos repositórios utilizando Eloquent ORM para interagir com o banco de dados.
  - **Providers**: Integrações com APIs externas como **The Movie DB**. Cada integração possui seu Adapter e implementação correspondente ao contrato definido na camada de **Domain**.
  - **Cache**: Implementações de camada de cache, para evitar consultas desencessárias ao banco de dados, melhorando assim o desempenho da aplicação.


- **DTO (Data Transfer Objects)**: Utilizados para transportar dados entre as camadas da aplicação, mantendo a integridade e padronização das informações.
- **Adapters**: Adapta a entrada e saída de dados entre a aplicação e os provedores externos, garantindo que a lógica de negócio não dependa de detalhes da implementação externa.
  
- **Tests**:
  - **Unitários**: Alguns testes para validar a lógica de negócios e interações entre as camadas internas, como UseCases.

### Componentes principais:

- **UseCases**:
  - `CreateMovieStatusUseCase`: Responsável por criar ou atualizar o status de um filme associado a um usuário.
  - `GetUserMoviesUseCase`: Obtém a lista de filmes associados ao usuário e seus respectivos status.
  - `GetMovieDetailsUseCase`: Busca detalhes extras de um filme através da integração com APIs externas, caso necessário.

- **Adapters e Integrações**:
  - **TheMovieDbApiProvider**: Adapter responsável pela integração com a API do The Movie DB. Ele implementa a interface `MovieApiRepositoryInterface` e adapta a resposta da API para o formato esperado pelo sistema.
  - O sistema foi construído de forma a permitir a adição de novos provedores de API, bastando implementar a interface de `MovieApiRepositoryInterface`.

### Padrões Utilizados:

- **Clean Architecture**: A lógica de negócios foi isolada em **UseCases**, e a comunicação com as infraestruturas externas, como APIs e banco de dados, é feita através de **Adapters** e **Repositories**, garantindo que o núcleo do sistema seja independente.
  
- **SOLID**: Todos os componentes seguem os princípios SOLID, garantindo baixo acoplamento, alta coesão e facilidade de manutenção e extensibilidade.

- **DTOs (Data Transfer Objects)**: Utilizados para transportar dados entre as camadas de forma padronizada.

- **Repository Pattern**: Padrão de repositório foi utilizado para abstrair o acesso a dados. Isso permite a troca de implementação de persistência sem impacto na lógica de negócios.

## Endpoints Principais
Aqui estão os principais endpoints da API:

- **POST /api/auth/login**: Realiza a autenticação do usuário.
- **POST /api/auth/register**: Registra um novo usuário.
- **POST /api/auth/refresh**: Atualiza o token JWT.
- **GET /api/movies**: Lista todos os filmes.
- **GET /api/user/movies**: Lista todos os filmes do usuário.
- **POST /api/movies**: Adiciona um novo filme.
- **PUT /api/movies/{id}/status**: Atualiza o status do filme (assistido, favorito, etc.).

## Importar Collection do Insomnia

Para facilitar os testes dos endpoints, você pode importar a collection do Insomnia fornecida.

### Instruções para Importar:

1. Baixe a [Collection do Insomnia](./docs/Insomnia_2024-09-09_the_movie_db.json).
2. Abra o Insomnia e vá até o menu **Application** > **Preferences** > **Data** > **Import Data** > **From File**.
3. Selecione o arquivo `filmes-collection.json` que você acabou de baixar.
4. Após importar, você terá acesso a todos os endpoints e exemplos de requisições já configurados para testar a API.

---


### Configuração e Instalação

#### Pré-requisitos

1. **Docker** e **Docker Compose** instalados.
2. **Chave de API** do **The Movie DB** ou outro provedor de filmes.

#### Instalação

Siga os passos abaixo para configurar o ambiente localmente:

1. Clone o repositório:

```bash
git clone https://github.com/julioolver/the-movie-db-test-api.git
```

```bash
cd the-movie-db-test-api
```


1. Execute os comandos para instalar as dependências e rodar o ambiente com Docker:

```bash
docker compose up --build

ou

docker-compose up --build
```

2. Atualize o `.env` e preencha as variáveis de ambiente necessárias, como a chave da API para o **The Movie DB**: THE_MOVIE_DB_API_KEY (deixei a minha como exemplo, caso não tenham cadastro na plataforma).

#### Executando a Aplicação
- Após a instalação e configuração, a aplicação estará disponível em:

```bash
http://localhost:8088
```

#### Autor
- Julio Cesar Oliveira da Silva