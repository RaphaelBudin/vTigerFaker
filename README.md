# TODO

# Problemas Conhecidos

## Curl
Caso as requisições CURL retornem NULL sem motivo aparente, e você estiver no Windows, provavelmente o Windows não conseguiu achar os certificados corretos. Siga os passos a seguir para correção:
1 - Abra o Windows Terminal
2 - Digite o comando, sem aspas, "php --ini"
3 - Anote o caminho que o php.ini está armanzenado
4 - Acesse o link https://curl.se/docs/caextract.html e baixe o cacert.pem, salve em algum lugar de fácil acesso (no meu caso, "C:/utils/")
5 - Adicione a seguinte linha no php.ini, sem aspas, curl.cainfo="C:/utils/cacert.pem"
6 - Pronto

## .ENV
Algumas palavras chaves são reservadas ao ambiente do Windows.
No arquivo .env, o USER_NAME não pode ser substituído por USERNAME, pois USERNAME é reservado pelo Windows e trará o nome do usuário logado no Windows ao invés da variável de ambiente