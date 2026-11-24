<?php

// Textos do starter em português. pt_AO, pt_PT e pt_BR leem este ficheiro
// (pt_AO → pt → en); ponha em lang/pt_AO.php (ou pt_BR.php) só o que mudar.
return [
    // Comuns
    'conta.email'                   => 'Email',
    'conta.palavra_passe'           => 'Palavra-passe',
    'conta.confirmar_palavra_passe' => 'Confirmar a palavra-passe',
    'conta.criar'                   => 'Criar conta',
    'conta.entrar'                  => 'Entrar',
    'conta.sair'                    => 'Sair',

    // Entrar
    'entrar.subtitulo'      => 'Bem-vindo de volta.',
    'entrar.esqueceu'       => 'Esqueceu-se da palavra-passe?',
    'entrar.erro'           => 'O email ou a palavra-passe não estão corretos.',
    'sair.feito'            => 'Sessão terminada.',

    // Registo
    'registo.subtitulo'     => 'Leva menos de um minuto.',
    'registo.nome'          => 'Nome',
    'registo.palavra_passe' => 'Palavra-passe (mínimo 8 caracteres)',
    'registo.tem_conta'     => 'Já tem conta?',

    // Recuperar e redefinir a palavra-passe
    'recuperar.titulo'      => 'Recuperar a palavra-passe',
    'recuperar.subtitulo'   => 'Indique o seu email e enviamos um link para definir uma nova palavra-passe.',
    'recuperar.enviar'      => 'Enviar link',
    'recuperar.voltar'      => 'Voltar a entrar',
    'recuperar.enviado'     => 'Se o email estiver registado, enviámos um link para redefinir a palavra-passe.',
    'recuperar.limite'      => 'Demasiados pedidos. Tente novamente dentro de alguns minutos.',
    'redefinir.titulo'      => 'Nova palavra-passe',
    'redefinir.subtitulo'   => 'Escolha uma palavra-passe com pelo menos 8 caracteres.',
    'redefinir.nova'        => 'Nova palavra-passe',
    'redefinir.guardar'     => 'Guardar a palavra-passe',
    'redefinir.invalido'    => 'O link para redefinir a palavra-passe não é válido ou já expirou. Peça um novo.',
    'redefinir.feito'       => 'Palavra-passe alterada. Já pode entrar.',

    // Verificação do email
    'verificar.titulo'      => 'Confirme o seu email',
    'verificar.texto'       => 'Enviámos um link para :email. Abra-o para ativar a conta. O link é válido durante uma hora.',
    'verificar.reenviar'    => 'Enviar outro link',
    'verificar.reenviado'   => 'Enviámos um novo link para o seu email.',
    'verificar.feito'       => 'Email confirmado. Obrigado!',
    'verificar.invalido'    => 'Este link não é válido ou já expirou.',

    // Painel
    'painel.titulo'         => 'Painel',
    'painel.ola'            => 'Olá, :nome',
    'painel.criada'         => 'Conta criada',
    'painel.confirmado'     => 'Email confirmado',

    // Página inicial
    'inicio.titulo'         => 'A sua aplicação',
    'inicio.titulo_destaque'=> 'já tem contas.',
    'inicio.lead'           => 'Registo, entrada, recuperação de palavra-passe e verificação de email, prontos a usar. Edite :ficheiro para mudar esta página.',
    'inicio.passo1.titulo'  => 'Autenticação',
    'inicio.passo1.texto'   => 'Controladores em :controladores, vistas em :vistas.',
    'inicio.passo2.titulo'  => 'Email',
    'inicio.passo2.texto'   => 'Em desenvolvimento os emails vão para o log. Para enviar a sério, configure o SMTP.',
    'inicio.passo3.titulo'  => 'Em português',
    'inicio.passo3.texto'   => 'Modelo, consultas, rotas, vistas e mensagens em português.',
    'inicio.ir_painel'      => 'Ir para o painel →',
    'inicio.criar_conta'    => 'Criar conta →',

    // Emails
    'email.ola'                 => 'Olá :nome,',
    'email.verificar.assunto'   => 'Confirme o seu email',
    'email.verificar.texto'     => 'Confirme o seu email para ativar a conta em :app:',
    'email.verificar.botao'     => 'Confirmar o email',
    'email.verificar.rodape'    => 'O link é válido durante uma hora. Se não criou esta conta, ignore esta mensagem.',
    'email.redefinir.assunto'   => 'Redefinir a palavra-passe',
    'email.redefinir.texto'     => 'Recebemos um pedido para redefinir a palavra-passe da sua conta em :app:',
    'email.redefinir.botao'     => 'Definir uma nova palavra-passe',
    'email.redefinir.rodape'    => 'O link é válido durante 60 minutos e só pode ser usado uma vez. Se não fez este pedido, ignore esta mensagem: a palavra-passe atual continua a valer.',
];
