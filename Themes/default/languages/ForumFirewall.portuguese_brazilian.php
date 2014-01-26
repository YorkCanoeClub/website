<?php
/**********************************************************************************
* ForumFirewall.english.php - PHP language file for ForumFirewall mod
* Version 1.1.5 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
* Translator portuguese_brazilian for Darkness
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

$txt['forumfirewall_cversion_mod'] = '1.1.5';
$txt['forumfirewall'] = 'Forum Firewall';
$txt['forumfirewall_config'] = 'log Visitantes';
$txt['forumfirewall_admin'] = 'Forum Firewall Admin';
$txt['forumfirewall_general'] = 'Configurações Gerais';
$txt['forumfirewall_admin_desc'] = 'Configure e Gestão';
$txt['forumfirewall_settings_title'] = 'Configurações';
$txt['forumfirewall_settings_desc'] = 'Esta página permite que você altere as configurações básicas para o seu Firewall Fórum. Tenha muito cuidado com essas configurações, pois pode tornar o fórum desfuncional.';
$txt['forumfirewall_reports_desc'] = 'Examine o Log de visitantes FireWall';
$txt['forumfirewall_about_title'] = 'sobre';
$txt['forumfirewall_about_desc'] = 'Sobre Forum Firewall';
$txt['forumfirewall_version_c'] = 'Versão/Creditos';
$txt['forumfirewall_cversion'] = 'Versão';
$txt['forumfirewall_cache_duration'] = 'Duração do Cache';
$txt['forumfirewall_salt'] = 'Salt única';
$txt['forumfirewall_sql_reason'] = 'Repetidos';
$txt['forumfirewall_reason0'] = 'Caracteres não permitidos';
$txt['forumfirewall_reason1'] = ' na lista Proxy';
$txt['forumfirewall_reason2'] = ' no Proxy escondido';
$txt['forumfirewall_settings_sub'] = 'Configurações';
$txt['forumfirewall_enable'] = 'Permitir o teste';
$txt['forumfirewall_enable_dos'] = 'Ataque DOS';
$txt['forumfirewall_enable_inj'] = 'Habilitar teste de injeção';
$txt['forumfirewall_enable_xxs'] = 'Habilitar inspeção XSS';
$txt['forumfirewall_enable_header'] = 'Habilitar cabeçalho inspeção';
$txt['forumfirewall_enable_country'] = 'Habilitar teste de país';
$txt['forumfirewall_in_geoip'] = 'GeoIP';
$txt['forumfirewall_enable_bypass'] = 'Habilitar proteção Bypass';
$txt['forumfirewall_ip'] = 'Endereço IP';
$txt['forumfirewall_ip_title'] = 'Endereço IP';
$txt['forumfirewall_robots_title'] = 'Robots.txt';
$txt['forumfirewall_enable_robots'] = 'Habilitar Robots.txt Valida&ccedil;&atilde;o';
$txt['forumfirewall_test_robots'] = 'Robots a ser testado';
$txt['forumfirewall_robotstxt_action'] = 'A&ccedil;&otilde;es de Robots.txt';
$txt['forumfirewall_port_title'] = 'Portas';
$txt['forumfirewall_enable_rmtport'] = 'Habilitar habilitação de porta remota';
$txt['forumfirewall_enable_svrport'] = 'Habilitar Validação da porta de Servidor';
$txt['forumfirewall_good_ser_ports'] = 'Lista de porta do servidor';
$txt['forumfirewall_enable_check_ip'] = 'Habilitar validação de IP';
$txt['forumfirewall_enable_proxy'] = 'Revisão de lista de Proxy';
$txt['forumfirewall_enable_block'] = 'Violações de bloco';
$txt['forumfirewall_bypass'] = 'informação de Proxy';
$txt['forumfirewall_domain'] = 'nome do Dominio';
$txt['forumfirewall_logging'] = 'Logging';
$txt['forumfirewall_mauthor'] = 'Autor: <a href="http://www.eastcoastrollingthunder.com/">butchs</a>';
$txt['forumfirewall_empty'] = 'Não existem registos nessa faixa';
$txt['forumfirewall_log_title'] = 'Reports';
$txt['forumfirewall_event_title'] = 'Detalhes de evento';
$txt['forumfirewall_log_id'] = 'ID';
$txt['forumfirewall_log_ip'] = 'IP';
$txt['forumfirewall_log_date'] = 'DATA';
$txt['forumfirewall_log_headers'] = 'CABEÇALHO';
$txt['forumfirewall_log_result'] = 'RESÃO';
$txt['forumfirewall_report_denied_title'] = 'Visitantes';
$txt['forumfirewall_rec_disp'] = 'Exibindo registro(s)';
$txt['forumfirewall_to'] = ' para ';
$txt['forumfirewall_from'] = 'de ';
$txt['forumfirewall_rec_tot'] = ' total ';
$txt['forumfirewall_type_den'] = '(Recorde de visitantes).';
$txt['forumfirewall_colin'] = ': ';
$txt['forumfirewall_injection'] = 'injeção SQL';
$txt['forumfirewall_cookie'] = 'Cross-Site Scripting';
$txt['forumfirewall_header'] = 'HTTP Ataques de cabeçalho';
$txt['forumfirewall_country'] = 'Identificação de País';
$txt['forumfirewall_uri_chars'] = 'Caracteres URI permitidos';
$txt['forumfirewall_exploits'] = 'lista de injeção';
$txt['forumfirewall_xxs'] = 'XSS Eventos';
$txt['forumfirewall_referer_attack'] = 'Referencia do Ataque';
$txt['forumfirewall_ua_attack'] = 'Ataques de User-Agent';
$txt['forumfirewall_entity_attack'] = 'Pedido de ataque de Entidade';
$txt['forumfirewall_bad_countries'] = 'País';
$txt['forumfirewall_real_ip'] = 'Visitante chamada IP para Proxy';
$txt['forumfirewall_header_id'] = 'Proxy Header ID';
$txt['forumfirewall_country_id'] = 'Código do país através de cabeçalhos';
$txt['forumfirewall_enable_email'] = 'Notificar Administrador';
$txt['forumfirewall_dos_attack'] = 'Ataque DOS';
$txt['forumfirewall_enable_ua'] = 'User-Agent de Inspeção.';
$txt['forumfirewall_email_diabled_0'] = 'Nunca';
$txt['forumfirewall_email_ddos_1'] = 'Enviar e-mail apenas na tentativa DOS';
$txt['forumfirewall_email_all_2'] = 'Enviar e-mails em cada entrada de log';
$txt['forumfirewall_trigger'] = 'Gatilho (#/seg)';
$txt['forumfirewall_never_0'] = 'Nunca';
$txt['forumfirewall_1hr_1'] = '1 Hora';
$txt['forumfirewall_24hr_2'] = '24 Horas';
$txt['forumfirewall_1wk_3'] = '1 Semana';
$txt['forumfirewall_permanent_4'] = 'Permanente';
$txt['forumfirewall_good_ua'] = 'User-Agent Whitelist';
$txt['forumfirewall_longterm_ban'] = 'Ban longo prazo';
$txt['forumfirewall_enable_admin'] = 'Ativar a confirmação de IP Admin';
$txt['forumfirewall_admin_ip_lo'] = 'Admin IP Baixo';
$txt['forumfirewall_admin_ip_hi'] = 'Admin IP Alto';
$txt['forumfirewall_admin_domain'] = 'Nome de Domínio Admin';
$txt['forumfirewall_theadmin'] = 'o ADM1N';
$txt['forumfirewall_nospam'] = '~n0spam[at]n0spam~';
$txt['forumfirewall_dot'] = '~[d0t]~';
$txt['forumfirewall_dash'] = '~[da5h]~';
$txt['forumfirewall_mailto'] = 'mailto:';
$txt['result0'] = '!';
$txt['result1'] = 'Bypass tentativa!';
$txt['result2'] = 'Cortar:  ';
$txt['result3'] = 'Ataque DOS!';
$txt['result4'] = 'iP inválido';
$txt['result5'] = 'País não aprovado: ';
$txt['result6'] = 'Cookie ruim: ';
$txt['result7'] = 'User-Agent Mau!';
$txt['result8'] = 'IP Admin inválido:  ';
$txt['result9'] = 'Referencia do Ataque:  ';
$txt['result10'] = 'Ataque de User-Agent:  ';
$txt['result11'] = 'Porta de Acesso inválida: ';
$txt['result13'] = 'Pedido de entrada do Attack:  ';
$txt['result14'] = 'Ataque Robot!';
$txt['result2a'] = 'Tentativa de hacking foi bloqueado!';
$txt['forumfirewall_block'] = ' Mod para SMF bloqueou um visitante!';
$txt['forumfirewall_for'] = ' por ';
$txt['forumfirewall_register_globals'] = 'Risco de segurança:  no register_globals!';
$txt['forumfirewall_magic_quotes'] = 'Risco de segurança: no magic_quotes!';
$txt['result6a'] = 'Os cookies deste site estão infectados! Por favor, excluí-los e voltar!';
$txt['forumfirewall_msupport'] = 'Para apoiar o autor mod e inspirar futuras atualizações / melhorias neste mod.<br /><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=UJTMMF8FKGLZ6&lc=US&item_name=butchs%2f%20continued%20updates&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"></a><br /><br />Copyright info & link deve permanecer intacto!  Eles só podem ser removidos através de Autor/Criadores aprovação ou fornecendo uma doação de US $ 10 dólares ou mais para o autor / criador.';
$txt['forumfirewall_oview'] = 'Fórum Firewall oferece 13 testes para o operador forum avançadas que protegem contra ataques de hackers.  Fórum Firewall é um suplemento existentes anti métodos de hacking e não deve ser a única linha de proteção.  Um exemplo de  proteção pode ser a seguinte:<ul type="circle"><li>Proxy Firewall.</li><li>Htaccess proteção, como o bloqueio de endereços ip desagradável, CrawlProtect e GeoIP.</li><li>Forum Firewall mod.</li><li>Bad Behavior mod.</li><li>Project Honeypot.</li><li>Stop Spammer.</li></ul><br />Essa proteção não vai parar um hacker determinado mas ele só pode enviá-los à procura de alvos mais fáceis.<br /><hr /><br />Algumas das características deste mod são:<ol type="a"><li>Compatível com CloudFlare e Proxies outros.</li><li>Verifica o status de globals registrar e magic quotes.</li><li>Quer logs ou violações blocos.</li><li>Detecta e automaticamente decodifica utf8 para o teste.</li><li>Protege contra hacking cookie admin.</li><li>Protege contra IP spoofing admin.</li><li>Vou enviar um e-mail para o admin, em DOS ou a cada tentativa de violação.</li><li>Construído em cache criptografada. É recomendado que você use esse recurso desde Fórum Firewall usa o cache para determinar se há uma violação DOS.  Ponto de ajuste mínimo é de 20 segundos.</li><li>Proteção DOS.  Olha para a UA e se for bloqueado não vai permitir o acesso.   Além disso, há um recurso onde ele olha para a taxa (batidas por segundo) que o visitante acessa o site e compara com uma lista de permissões e proibições, em seguida, o visitante ou bandeiras com base nas configurações.  Inclui a capacidade de proibição através do sistema SMF proibição.</li><li>Validação de endereço IP - Verifica todos os endereços IP na lista de proxy IP de visitantes.</li><li>Proteção Cross Site Scripting.  O mod olha para entrada de visitantes cookies site local para ataques Cross-Site Scripting. Há uma varredura automática em tarefas agendadas que inspeciona arquivos de imagem nos anexos, smilies e pastas de imagens do tema, infecções uma vez por semana.  A última característica fornece apenas uma mensagem de aviso.  Se você tem chances de infecções são mais generalizada do que você pensa e os arquivos php devem ser verificados.</li><li>Cabeçalho de Proteção contra ataque HTTP - O mod inspeciona Entidade de entrada, Referência e User-Agent de tráfego para tentativas de hacking.</li><li>Porta de proteção Spoofing.</li><li>injeção SQL. Tudo está uri são inspecionados em busca de sinais de caracteres permitido uri e tentativas de injeção SQL.</li><li>códigos de País. Este recurso é limitado. Ele vai trabalhar com GeoIP servidor baseado e CloudFlare.</li><li>Interface proxy. Vai testar o endereço ip visitantes contra as configurações de proxy e evitar tentativas de desvio.  Isso só funciona com um endereço IP estático.</li></ol>';
?>