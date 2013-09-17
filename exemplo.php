<?php

require_once './VP_Cache.php';




echo "<br /><br /> Dados salvando por 2 minutos ";
VpCache::save('teste1', 'Hello World!', 2);

echo "<br /><br /> Buscando dados no cache: ";
echo VpCache::get('teste1');

echo "<br /><br /> Deletando dados do cache!";
VpCache::delete('teste1');


echo "<br /><br /> Usando funcao remenber! ";
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
//VpCache::delete('post');
// display the cached array
echo '<pre>#';
var_dump($result);
echo '<pre>#';

echo "<br /><br /> Esse arquivo sera chamado atraves da classe VP_Cache <br/>
         Apenas uma vez e depodeis seus dados serao salvos no cache";

$result_include = VpCache::rememberInclude('include-exemplo.php', 2);
echo $result_include;

echo "<br /> Experimente deletar do cache a key post ou aguarde 1 mim e vc vera que novamente ele ira executar a funcao.
    <br />para isso coloque a baixo: <br />";
echo 'VpCache::delete("post"); ';
//VpCache::delete('post'); //descomente para testar
