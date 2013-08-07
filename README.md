VP_Cache
========

VP_Cache

## Instalação ##

```php
require_once './VP_Cache.php';
```

## Dados salvo por 2 minutos  ###
```php

VpCache::save('teste1', 'Hello World!', 2);

```php

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
                                array('alunos' => 'valmir', 'email' => 'valmir.php@gmail.com', 'idade' => 22),
                                array('alunos' => 'Pafuncio', 'email' => 'pafuncio.php@gmail.com', 'idade' => 56),
                    );
                    
                    //retornando o valor para funcao anonima
                    return $value;
                });
//VpCache::delete('post');
// display the cached array
echo '<pre>#';
print_r($result);
echo '<pre>#';

```
## deletando o cache remenber ##

Experimente deletar do cache a key post ou aguarde 1 mim e vc vera que novamente ele ira executar a funcao 
para isso coloque a baixo:

```php
    VpCache::delete("post"); 
```