# Partidas de Futebol
Aplicação web para cadastro e gerenciamento de partidas de futebol.

## Tecnologias
Utilização de Laravel na arquitetura MVC com blades.

## Funcionalidades
* <b> Visualizar partidas: </b> Listagem das partidas disponíveis na base de dados, ordenadas da data de patida mais atual para a mais antiga, com botões de ação.
  * O botão azul redireciona para a tela de jogadores confirmados e pode ser clicado a qualquer momento para visualizar os jogadores da partida ou adicionar/confirmar outros jogadores.
  * O botão verde redireciona para a tela de geração de equipes e somente pode ser clicado quando houver 2 ou mais jogadores confirmados para a partida.
  
![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/421d0e69-3b56-4cf6-9b44-c4924a02b80c)

* <b> Cadastro de partida: </b> Formulário para preencher e criar uma nova partida.
  * Todos os campos são obrigatórios.
  * A combinação de rótulo e data deve ser única. Rótulos e dadas podem ser repetidos, desde que não simultaneamente.
  * Obs: O rótulo terá a primeira letra transformada em maiúscula, caso já não for. A validação considera o rótulo transformado.
  
![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/bc3410a0-0fdf-4d7a-92d8-279edcec7a75)

* <b> Visualizar jogadores confirmados para a partida: </b> Listagem em ordem alfabética dos jogadores com a presença confirmada para a partida.

![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/1f41fe31-d639-4e8c-8b52-fad33e16122b)

* <b> Confirmação de jogadores: </b> Listagem em ordem alfabética dos jogadores na base de dados que não confirmaram presença para a partida, com botão de ação para a confirmação.

![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/b93885a1-9e3b-4c45-8c66-cfea36c0e402)


* <b> Cadastro de jogadores: </b> Formulário para preencher e criar um novo jogador.
  * Todos os campos são obrigatórios.
  * O nome do jogador deve ser único.
  * Obs: O nome terá a primeira letra de todas as palavras transformadas em maiúsculas, caso já não forem. A validação considera o nome transformado.

![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/6bfbc86d-d44c-4dba-8582-4e328fbf4f66)

* <b> Geração de equipes: </b> Botão para gerar equipes de acordo com os jogadores previamente confirmados para a partida e o número de jogadores por equipe definido pelo usuário. Listagem das equipes e banco de reservas em ordem de habilidade dos jogadores da mais alta para a mais baixa.
  * O número de jogadores por equipe é obrigatório para gerar equipes.
  * O número de jogadores por equipe não pode ser maior que a metade da quantidade de jogadores confirmados para a partida.
  * Se o número de jogadores por equipe for menor que a metade da quantidade de jogadores confirmados para a partida, os jogadores restantes com menor habilidade (com exceção dos goleiros, caso haja dois ou mais), ficarão no banco de reservas.
  * Regras da geração:
    * Caso houver dois goleiros ou mais, os dois goleiros com maior habilidade serão alocados nos times 1 e 2, respectivamente.
    * Caso houver somente um goleiro, ele será considerado como um jogador de campo.
    * Caso houver mais de dois goleiros, os goleiros que não forem inicialmente alocados em um time serão considerados como jogadores de campo.
    * Os jogadores de campo serão balanceados por habilidade, da seguinte forma:
      * O time 1 recebe o jogador mais habilidoso e o jogador menos habilidoso da lista.
      * O time 2 recebe o próximo jogador mais habilidoso e menos habilidoso da lista.
      * O processo é repetido até que não haja jogadores restantes para completar a quantidade de jogadores por equipe informada.
      * No caso de faltar somente dois jogadores por equipe, os times 1 e 2 recebem os dois jogadores restantes, com o mais habilidoso indo para o time 2.
      * O balanceamento não é exato e não considera a habilidade dos goleiros caso houver dois ou mais, visto que os dois goleiros mais habilidosos sempre serão divididos entre os times 1 e 2, independentemente da habilidade do restante dos jogadores.

![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/261396f9-68c0-45e0-ab48-55fe5d96b713)

![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/a9635a8b-d82d-45e7-a61c-43e599557880)

![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/802ebab6-9a17-4207-aef7-3a3d563a4b23)
![image](https://github.com/LaraAyrolla/Soccer-Games/assets/72223107/be7c46d9-f680-467d-84e8-26f4165791b7)

