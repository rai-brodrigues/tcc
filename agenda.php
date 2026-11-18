<?php
require_once __DIR__ . '/painel/verifica_login.php';

$perfil = $_SESSION['perfil'] ?? '';
$base = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? '/tcc' : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agenda | Gestão de Monitoramento</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base; ?>/css/estilo-global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

    <style>
        .fc .fc-daygrid-day.dia-aberto {
            background-color: rgba(25, 135, 84, 0.10);
        }

        .fc .fc-daygrid-day.dia-fechado {
            background-color: rgba(220, 53, 69, 0.13);
        }

        .legenda-agenda {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 18px;
            color: #fff;
        }

        .legenda-agenda span {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .indicador-aberto,
        .indicador-fechado {
            width: 13px;
            height: 13px;
            border-radius: 3px;
            display: inline-block;
        }

        .indicador-aberto {
            background-color: #198754;
        }

        .indicador-fechado {
            background-color: #dc3545;
        }
        body {
            background-color: #111 !important;
            color: #fff;
        }

        h2, h5 {
            color: #fff;
        }

        .text-muted {
            color: #aaa !important;
        }

        .card {
            background-color: #1a1a1a;
            color: #fff;
            border: 1px solid #292929;
            border-radius: 12px;
        }

        .form-label {
            color: #fff;
        }

        .form-control,
        .form-select {
            background-color: #222;
            color: #fff;
            border: 1px solid #444;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #222;
            color: #fff;
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-select option {
            background-color: #222;
            color: #fff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            border-color: #b02a37;
        }

        #calendario {
            background-color: #1a1a1a;
            padding: 18px;
            border-radius: 12px;
            color: #fff;
        }

        .fc {
            color: #fff;
        }

        .fc .fc-toolbar-title {
            color: #fff;
            font-size: 1.35rem;
        }

        .fc .fc-button-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .fc .fc-button-primary:hover,
        .fc .fc-button-primary:focus {
            background-color: #b02a37;
            border-color: #b02a37;
        }

        .fc .fc-button-primary:disabled {
            background-color: #69252d;
            border-color: #69252d;
        }

        .fc .fc-daygrid-day,
        .fc .fc-timegrid-slot,
        .fc .fc-scrollgrid {
            border-color: #444;
        }

        .fc .fc-daygrid-day-number,
        .fc .fc-col-header-cell-cushion {
            color: #fff;
            text-decoration: none;
        }

        .fc .fc-day-today {
            background-color: rgba(220, 53, 69, 0.12) !important;
        }

        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            padding: 2px;
        }

        .campo-obrigatorio {
            color: #ff6b6b;
        }

        .modal-content {
            background-color: #1a1a1a;
            color: #fff;
            border: 1px solid #444;
        }

        @media (max-width: 768px) {
            #calendario {
                padding: 8px;
            }

            .fc .fc-toolbar {
                flex-direction: column;
                gap: 10px;
            }

            .fc .fc-toolbar-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/includes_site/header.php'; ?>

<main class="container mt-4 mb-5">

    <h2>
        <i class="fa-solid fa-calendar-days text-danger"></i>
        Agenda
    </h2>

    <p class="text-muted">
        Organize e acompanhe os compromissos da academia.
    </p>

    <?php if (in_array($perfil, ['admin', 'instrutor'], true)): ?>

        <div class="card p-4 mt-4">

            <h5>
                <i class="fa-solid fa-calendar-plus text-danger"></i>
                Novo agendamento
            </h5>

            <form id="formAgenda">

                <div class="row">

                    <div class="col-md-6 mt-3">
                        <label class="form-label">
                            Título <span class="campo-obrigatorio">*</span>
                        </label>

                        <input
                            type="text"
                            id="tituloAgenda"
                            class="form-control"
                            maxlength="150"
                            placeholder="Ex.: Treino de pernas"
                            required
                        >
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Tipo de compromisso</label>

                        <select id="tipoAgenda" class="form-select" required>
                            <option value="treino">Treino</option>
                            <option value="avaliacao">Avaliação física</option>
                            <option value="atendimento">Atendimento</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>

                    <?php if ($perfil === 'admin'): ?>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Instrutor</label>

                            <select id="instrutorAgenda" class="form-select">
                                <option value="">Selecione um instrutor</option>
                            </select>
                        </div>

                    <?php endif; ?>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Aluno</label>

                        <select id="alunoAgenda" class="form-select">
                            <option value="">Nenhum aluno específico</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">
                            Data e horário de início
                            <span class="campo-obrigatorio">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            id="inicioAgenda"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">
                            Data e horário de término
                            <span class="campo-obrigatorio">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            id="fimAgenda"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label">Descrição</label>

                        <textarea
                            id="descricaoAgenda"
                            class="form-control"
                            rows="3"
                            placeholder="Informações adicionais sobre o compromisso..."
                        ></textarea>
                    </div>

                </div>

                <button type="submit" class="btn btn-danger mt-4">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Salvar agendamento
                </button>

            </form>

        </div>

    <?php elseif ($perfil === 'aluno'): ?>

        <div class="card p-3 mt-4">
            <p class="mb-0">
                <i class="fa-solid fa-circle-info text-danger"></i>
                Consulte aqui seus compromissos e horários com o instrutor responsável.
            </p>
        </div>

    <?php endif; ?>

    <div class="card p-3 mt-4">

        <h5 class="mb-3">
            <i class="fa-solid fa-calendar text-danger"></i>
            Calendário de compromissos
        </h5>

        <div class="legenda-agenda">
    <span>
        <i class="indicador-aberto"></i>
        Segunda a sexta: 06h às 22h
    </span>

    <span>
        <i class="indicador-fechado"></i>
        Sábado e domingo: fechado
    </span>
</div>

        <div id="mensagemAgenda" class="mb-3"></div>

        <div id="calendario"></div>

    </div>

</main>

<div class="modal fade" id="modalEditarAgenda" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fa-solid fa-calendar-pen text-danger"></i>
                    Editar agendamento
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body">

                <form id="formEditarAgenda">

                    <input type="hidden" id="editarIdAgenda">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Título</label>

                            <input
                                type="text"
                                id="editarTituloAgenda"
                                class="form-control"
                                maxlength="150"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>

                            <select id="editarTipoAgenda" class="form-select" required>
                                <option value="treino">Treino</option>
                                <option value="avaliacao">Avaliação física</option>
                                <option value="atendimento">Atendimento</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>

                        <?php if ($perfil === 'admin'): ?>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instrutor</label>

                                <select id="editarInstrutorAgenda" class="form-select">
                                    <option value="">Nenhum instrutor</option>
                                </select>
                            </div>

                        <?php endif; ?>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aluno</label>

                            <select id="editarAlunoAgenda" class="form-select">
                                <option value="">Nenhum aluno específico</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Início</label>

                            <input
                                type="datetime-local"
                                id="editarInicioAgenda"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Término</label>

                            <input
                                type="datetime-local"
                                id="editarFimAgenda"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>

                            <select id="editarStatusAgenda" class="form-select">
                                <option value="agendado">Agendado</option>
                                <option value="concluido">Concluído</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Descrição</label>

                            <textarea
                                id="editarDescricaoAgenda"
                                class="form-control"
                                rows="3"
                            ></textarea>
                        </div>

                    </div>

                    <div id="mensagemEditarAgenda" class="mb-3"></div>

                    <div class="d-flex justify-content-between flex-wrap gap-2">

                        <button
                            type="button"
                            id="btnExcluirAgenda"
                            class="btn btn-outline-danger"
                        >
                            <i class="fa-solid fa-trash"></i>
                            Excluir
                        </button>

                        <div class="d-flex gap-2">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                Cancelar
                            </button>

                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Salvar alterações
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . '/includes_site/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
    window.PERFIL_USUARIO = <?= json_encode($perfil) ?>;
    window.BASE_URL = <?= json_encode($base) ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const perfil = window.PERFIL_USUARIO;
    const apiAgenda = window.BASE_URL + '/painel/agenda_api.php';

    const calendarioElemento = document.getElementById('calendario');
    const mensagem = document.getElementById('mensagemAgenda');

    let calendario;
    let promessaOpcoesAgenda = Promise.resolve();

    function mostrarMensagem(texto, tipo = 'danger') {

        mensagem.innerHTML = '';

        const alerta = document.createElement('div');

        alerta.className = 'alert alert-' + tipo;
        alerta.textContent = texto;

        mensagem.appendChild(alerta);
    }

    function validarHorarioAcademia(inicio, fim) {
    if (!inicio || !fim) {
        return 'Informe a data e os horários.';
    }

    const dataInicio = new Date(inicio);
    const dataFim = new Date(fim);

    if (isNaN(dataInicio.getTime()) || isNaN(dataFim.getTime())) {
        return 'Informe datas e horários válidos.';
    }

    const dia = dataInicio.getDay();

    if (dia === 0 || dia === 6) {
        return 'A academia não aceita agendamentos aos sábados e domingos.';
    }

    if (dataFim.toDateString() !== dataInicio.toDateString()) {
        return 'O agendamento deve terminar no mesmo dia.';
    }

    const horaInicio = dataInicio.getHours() * 60 + dataInicio.getMinutes();
    const horaFim = dataFim.getHours() * 60 + dataFim.getMinutes();

    if (horaInicio < 360 || horaInicio >= 1320 || horaFim > 1320) {
        return 'O horário permitido é das 06h às 22h.';
    }

    if (dataInicio.getMinutes() !== 0 || dataFim.getMinutes() !== 0) {
        return 'Os agendamentos devem começar e terminar em horários cheios.';
    }

    if (dataFim <= dataInicio) {
        return 'O horário final deve ser posterior ao inicial.';
    }

    return '';
}

    function limparMensagem() {
        mensagem.innerHTML = '';
    }

    function mostrarMensagemEdicao(texto, tipo = 'danger') {

        const elemento = document.getElementById('mensagemEditarAgenda');

        elemento.innerHTML = '';

        const alerta = document.createElement('div');

        alerta.className = 'alert alert-' + tipo;
        alerta.textContent = texto;

        elemento.appendChild(alerta);
    }

    async function requisicaoAgenda(url, opcoes = {}) {

        const resposta = await fetch(url, {
            ...opcoes,
            headers: {
                'Content-Type': 'application/json',
                ...(opcoes.headers || {})
            }
        });

        const dados = await resposta.json();

        if (!resposta.ok) {
            throw new Error(dados.erro || 'Erro ao processar solicitação.');
        }

        return dados;
    }

    function dataParaCampo(data) {

        const ano = data.getFullYear();
        const mes = String(data.getMonth() + 1).padStart(2, '0');
        const dia = String(data.getDate()).padStart(2, '0');
        const hora = String(data.getHours()).padStart(2, '0');
        const minuto = String(data.getMinutes()).padStart(2, '0');

        return `${ano}-${mes}-${dia}T${hora}:${minuto}`;
    }

    async function carregarOpcoesAgenda() {

        if (!['admin', 'instrutor'].includes(perfil)) {
            return;
        }

        try {

            const respostaAlunos = await requisicaoAgenda(
                window.BASE_URL + '/painel/alunos_api.php'
            );

            const selectAluno = document.getElementById('alunoAgenda');
            const selectAlunoEdicao = document.getElementById('editarAlunoAgenda');

            respostaAlunos.forEach(function (aluno) {

                const opcao = document.createElement('option');

                opcao.value = aluno.id;
                opcao.textContent = aluno.nome;

                selectAluno.appendChild(opcao);

                const opcaoEdicao = document.createElement('option');

                opcaoEdicao.value = aluno.id;
                opcaoEdicao.textContent = aluno.nome;

                selectAlunoEdicao.appendChild(opcaoEdicao);
            });

            if (perfil === 'admin') {

                const respostaInstrutores = await requisicaoAgenda(
                    window.BASE_URL + '/painel/instrutores_api.php'
                );

                const selectInstrutor = document.getElementById('instrutorAgenda');
                const selectInstrutorEdicao = document.getElementById('editarInstrutorAgenda');

                respostaInstrutores.forEach(function (instrutor) {

                    const opcao = document.createElement('option');

                    opcao.value = instrutor.id;
                    opcao.textContent = instrutor.nome;

                    selectInstrutor.appendChild(opcao);

                    const opcaoEdicao = document.createElement('option');

                    opcaoEdicao.value = instrutor.id;
                    opcaoEdicao.textContent = instrutor.nome;

                    selectInstrutorEdicao.appendChild(opcaoEdicao);
                });
            }

        } catch (erro) {

            mostrarMensagem(
                'Não foi possível carregar alunos ou instrutores: ' + erro.message
            );

            throw erro;
        }
    }

    promessaOpcoesAgenda = carregarOpcoesAgenda();

    function carregarCalendario() {

        calendario = new FullCalendar.Calendar(calendarioElemento, {

            initialView: 'dayGridMonth',

            businessHours: {
    daysOfWeek: [1, 2, 3, 4, 5],
    startTime: '06:00',
    endTime: '22:00'
},

slotMinTime: '06:00:00',
slotMaxTime: '22:00:00',

dayCellClassNames: function(info) {
    const dia = info.date.getDay();

    if (dia === 0 || dia === 6) {
        return ['dia-fechado'];
    }

    return ['dia-aberto'];
},

            locale: 'pt-br',

            height: 'auto',

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },

            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },

            events: async function (info, sucesso, falha) {

                try {

                    const eventos = await requisicaoAgenda(apiAgenda);

                    const formatados = eventos.map(function (evento) {

                        return {

                            id: evento.id,

                            title: evento.titulo || 'Compromisso',

                            start: evento.inicio.replace(' ', 'T'),

                            end: evento.fim.replace(' ', 'T'),

                            backgroundColor:
                                evento.status === 'cancelado' ? '#666' :
                                evento.tipo === 'avaliacao' ? '#0d6efd' :
                                evento.tipo === 'atendimento' ? '#198754' :
                                evento.tipo === 'outro' ? '#6f42c1' :
                                '#dc3545',

                            borderColor: 'transparent',

                            extendedProps: {

                                tipo: evento.tipo,

                                descricao: evento.descricao,

                                status: evento.status,

                                aluno: evento.aluno_nome,

                                instrutor: evento.instrutor_nome,

                                idAluno: evento.id_aluno,

                                idInstrutor: evento.id_instrutor

                            }

                        };

                    });

                    sucesso(formatados);

                } catch (erro) {

                    falha(erro);

                    mostrarMensagem(erro.message);
                }
            },

            eventClick: async function (info) {

                const evento = info.event;
                const dados = evento.extendedProps;

                if (!['admin', 'instrutor'].includes(perfil)) {

                    let texto =
                        'Compromisso: ' + evento.title +
                        '\nTipo: ' + dados.tipo +
                        '\nInício: ' + evento.start.toLocaleString('pt-BR') +
                        '\nTérmino: ' +
                        (evento.end ? evento.end.toLocaleString('pt-BR') : 'Não informado') +
                        '\nStatus: ' + dados.status;

                    if (dados.aluno) {
                        texto += '\nAluno: ' + dados.aluno;
                    }

                    if (dados.instrutor) {
                        texto += '\nInstrutor: ' + dados.instrutor;
                    }

                    if (dados.descricao) {
                        texto += '\nDescrição: ' + dados.descricao;
                    }

                    alert(texto);

                    return;
                }

                try {

                    await promessaOpcoesAgenda;

                    document.getElementById('editarIdAgenda').value = evento.id;

                    document.getElementById('editarTituloAgenda').value = evento.title;

                    document.getElementById('editarTipoAgenda').value = dados.tipo;

                    document.getElementById('editarDescricaoAgenda').value =
                        dados.descricao || '';

                    document.getElementById('editarStatusAgenda').value = dados.status;

                    document.getElementById('editarAlunoAgenda').value =
                        dados.idAluno || '';

                    if (perfil === 'admin') {

                        document.getElementById('editarInstrutorAgenda').value =
                            dados.idInstrutor || '';
                    }

                    document.getElementById('editarInicioAgenda').value =
                        dataParaCampo(evento.start);

                    document.getElementById('editarFimAgenda').value =
                        evento.end ? dataParaCampo(evento.end) : '';

                    document.getElementById('mensagemEditarAgenda').innerHTML = '';

                    const modal = bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('modalEditarAgenda')
                    );

                    modal.show();

                } catch (erro) {

                    mostrarMensagemEdicao(
                        'Não foi possível abrir o agendamento: ' + erro.message
                    );
                }
            },

            dateClick: function (info) {
    if (!['admin', 'instrutor'].includes(perfil)) return;

    const dia = info.date.getDay();

    if (dia === 0 || dia === 6) {
        mostrarMensagem('A academia não funciona aos sábados e domingos.');
        return;
    }

    const inicio = document.getElementById('inicioAgenda');
    const fim = document.getElementById('fimAgenda');

    if (!inicio || !fim) return;

    let dataInicio;

    if (info.dateStr.length === 10) {
        dataInicio = info.dateStr + 'T08:00';
    } else {
        const hora = info.date.getHours();

        if (hora < 6 || hora >= 22) {
            mostrarMensagem('Os agendamentos são permitidos das 06h às 22h.');
            return;
        }

        dataInicio = info.dateStr.substring(0, 16);
    }

    const dataFim = new Date(dataInicio);
    dataFim.setHours(dataFim.getHours() + 1);

    if (dataFim.getHours() > 22 || dataFim.getHours() === 22 && dataFim.getMinutes() > 0) {
        mostrarMensagem('Não há horários disponíveis após as 22h.');
        return;
    }

    inicio.value = dataInicio;
    fim.value = dataFim.getFullYear() + '-' +
        String(dataFim.getMonth() + 1).padStart(2, '0') + '-' +
        String(dataFim.getDate()).padStart(2, '0') + 'T' +
        String(dataFim.getHours()).padStart(2, '0') + ':' +
        String(dataFim.getMinutes()).padStart(2, '0');

    document.getElementById('tituloAgenda').focus();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

        });

        calendario.render();
    }

    carregarCalendario();

    const formulario = document.getElementById('formAgenda');

    if (formulario) {

        formulario.addEventListener('submit', async function (event) {

            event.preventDefault();

            limparMensagem();

            const dados = {

                titulo: document.getElementById('tituloAgenda').value.trim(),

                tipo: document.getElementById('tipoAgenda').value,

                id_aluno: document.getElementById('alunoAgenda').value || null,

                inicio: document.getElementById('inicioAgenda').value,

                fim: document.getElementById('fimAgenda').value,

                descricao: document.getElementById('descricaoAgenda').value.trim()

            };

            if (perfil === 'admin') {

                dados.id_instrutor =
                    document.getElementById('instrutorAgenda').value || null;
            }

            const erroHorarioCadastro = validarHorarioAcademia(
                dados.inicio,
                dados.fim
            );

            if (!dados.titulo || erroHorarioCadastro) {
                mostrarMensagem(
                    erroHorarioCadastro || 'Preencha o título do agendamento.',
                    'danger'
                );
                return;
            }

            try {

                const resultado = await requisicaoAgenda(apiAgenda, {

                    method: 'POST',

                    body: JSON.stringify(dados)

                });

                mostrarMensagem(
                    resultado.mensagem || 'Agendamento salvo!',
                    'success'
                );

                formulario.reset();

                calendario.refetchEvents();

            } catch (erro) {

                mostrarMensagem(erro.message);
            }

        });
    }

    const formularioEdicao = document.getElementById('formEditarAgenda');

    formularioEdicao.addEventListener('submit', async function (event) {

        event.preventDefault();

        const dados = {

            id: document.getElementById('editarIdAgenda').value,

            titulo: document.getElementById('editarTituloAgenda').value.trim(),

            tipo: document.getElementById('editarTipoAgenda').value,

            id_aluno: document.getElementById('editarAlunoAgenda').value || null,

            inicio: document.getElementById('editarInicioAgenda').value,

            fim: document.getElementById('editarFimAgenda').value,

            status: document.getElementById('editarStatusAgenda').value,

            descricao: document.getElementById('editarDescricaoAgenda').value.trim()

        };

        if (perfil === 'admin') {

            dados.id_instrutor =
                document.getElementById('editarInstrutorAgenda').value || null;
        }

        const erroHorarioEdicao = validarHorarioAcademia(
            dados.inicio,
            dados.fim
        );

        if (!dados.id || !dados.titulo || erroHorarioEdicao) {
            mostrarMensagemEdicao(
                erroHorarioEdicao || 'Preencha o título do agendamento.',
                'danger'
            );
            return;
        }

        try {

            const resultado = await requisicaoAgenda(apiAgenda, {

                method: 'PUT',

                body: JSON.stringify(dados)

            });

            const modalElemento = document.getElementById('modalEditarAgenda');

            const modal = bootstrap.Modal.getInstance(modalElemento);

            if (modal) {
                modal.hide();
            }

            mostrarMensagem(
                resultado.mensagem || 'Agendamento atualizado com sucesso!',
                'success'
            );

            calendario.refetchEvents();

        } catch (erro) {

            mostrarMensagemEdicao(erro.message);
        }

    });

    const botaoExcluir = document.getElementById('btnExcluirAgenda');

    botaoExcluir.addEventListener('click', async function () {

        const id = document.getElementById('editarIdAgenda').value;

        if (!id) {
            return;
        }

        const confirmar = confirm(
            'Tem certeza de que deseja excluir este agendamento?'
        );

        if (!confirmar) {
            return;
        }

        try {

            const resultado = await requisicaoAgenda(
                apiAgenda + '?id=' + encodeURIComponent(id),
                {
                    method: 'DELETE'
                }
            );

            const modalElemento = document.getElementById('modalEditarAgenda');

            const modal = bootstrap.Modal.getInstance(modalElemento);

            if (modal) {
                modal.hide();
            }

            mostrarMensagem(
                resultado.mensagem || 'Agendamento excluído com sucesso!',
                'success'
            );

            calendario.refetchEvents();

        } catch (erro) {

            mostrarMensagemEdicao(erro.message);
        }

    });

});
</script>

</body>
</html>
