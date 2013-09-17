VP_Cache
========

Classe de cache para PHP, facil implementação.


## Instalação ##

```php
require_once 'VP_Cache.php';
```

## Salvando dados por 2 minutos  ###

```php
VpCache::save('teste1', 'Hello World!', 2);
```

## Buscando dados no cache ##
```php
echo VpCache::get('teste1');
```

## Deletando dados do cache! ##

```php
VpCache::delete('teste1');
```

## Usando funcao remenber! ##

```php

$result = VpCache::remember('post', 1, function() {
                    /**
                     * Aqui dentro vc poderia por um consulta de banco de dados, 
                     * usar um CURL, ou qualquer outro tidpo de consulta
                     * o segredo é sempre retorna o valor no final.
                     */
                    echo "<p><strong>Fazendo busca, lembre essa funcao so vai ser executada uma vez, apos atualizar a pagina eu sumirei :p </p></strong>";
                    
                    $value = array(
                                array('alunos' => 'valmir', 'email' => 'teste@gmail.com', 'idade' => 22),
                                array('alunos' => 'Pafuncio', 'email' => 'teste2@gmail.com', 'idade' => 56),
                    );
                    
                    //retornando o valor para funcao anonima
                    return $value;
                });

// Exibindo o retorno
var_dump($result);


```

## Salvando em cache um include de um arquivo php ##
