const BASE_URL = window.location.pathname.startsWith('/tcc/') ? '/tcc' : '';

const API_ALUNOS = `${BASE_URL}/painel/alunos_api.php`;
const API_TREINOS = `${BASE_URL}/painel/treinos_api.php`;
const API_AVALIACOES = `${BASE_URL}/painel/avaliacoes_api.php`;
const API_INSTRUTORES = `${BASE_URL}/painel/instrutores_api.php`;

let alunos = [];
let treinos = [];
let avaliacoes = [];
let instrutores = [];

function esc(valor) {
    return String(valor ?? '').replace(/[&<>"']/g, caractere => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[caractere]);
}

function nomeAluno(id) {
    const aluno = alunos.find(a => Number(a.id) === Number(id));
    return aluno ? aluno.nome : 'Aluno não encontrado';
}

function atualizarDashboard() {
    renderDashboard();
}

async function carregarAlunos() {
    try {
        console.log('FitControl: carregando dados...');

        const resposta = await fetch(API_ALUNOS);

        if (!resposta.ok) {
            const detalhe = await resposta.text();
            console.error('Erro na API de alunos:', resposta.status, detalhe);
            throw new Error('Erro ao buscar alunos. Código: ' + resposta.status);
        }

        alunos = await resposta.json();

        console.log('Alunos carregados:', alunos);

        renderAlunos();

        preencherAlunos('alunoTreino');
        preencherAlunos('alunoAvaliacao');

        await carregarTreinos();
        await carregarAvaliacoes();
        await carregarInstrutores();

        renderAlunos();
        renderTreinos();
        renderAvaliacoes();
        renderInstrutores();
        renderRelatorios();
        renderDashboard();

    } catch (erro) {
        console.error('Erro ao carregar dados:', erro);
        alert('Erro ao carregar dados: ' + erro.message);
    }
}

function renderDashboard() {
    const total = document.getElementById('total');
    const totalTreinos = document.getElementById('totalTreinos');
    const totalInstrutores = document.getElementById('totalInstrutores');
    const totalAvaliacoes = document.getElementById('totalAvaliacoes');

    if (total) total.textContent = alunos.length;
    if (totalTreinos) totalTreinos.textContent = treinos.length;
    if (totalInstrutores) totalInstrutores.textContent = instrutores.length;
    if (totalAvaliacoes) totalAvaliacoes.textContent = avaliacoes.length;

    renderRelatorios();
}



function editarAluno(id) {
    const aluno = alunos.find(a => Number(a.id) === Number(id));

    if (!aluno) {
        alert('Aluno não encontrado.');
        return;
    }

    const elementoModal = document.getElementById('modalEditarAluno');

    if (!elementoModal || typeof bootstrap === 'undefined') {
        alert('A janela de edição não foi encontrada.');
        return;
    }

    document.getElementById('editarId').value = aluno.id;
    document.getElementById('editarNome').value = aluno.nome || '';
    document.getElementById('editarIdade').value = aluno.idade || '';
    document.getElementById('editarEmail').value = aluno.email || '';
    document.getElementById('editarTelefone').value = aluno.telefone || '';
    document.getElementById('editarPlano').value = aluno.plano || '';

    const selectInstrutor = document.getElementById('editarInstrutor');

    selectInstrutor.innerHTML = '<option value="">Sem instrutor</option>';

    instrutores.forEach(instrutor => {
        const option = document.createElement('option');

        option.value = instrutor.id;
        option.textContent = instrutor.nome;

        selectInstrutor.appendChild(option);
    });

    selectInstrutor.value = aluno.id_instrutor || '';

    bootstrap.Modal.getOrCreateInstance(elementoModal).show();
}



document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('formEditarAluno');

    if (!formulario) return;

    formulario.addEventListener('submit', async function (event) {
        event.preventDefault();

        const botaoSalvar = formulario.querySelector('button[type="submit"]');

        const dados = {
            id: Number(document.getElementById('editarId').value),
            nome: document.getElementById('editarNome').value.trim(),
            idade: Number(document.getElementById('editarIdade').value),
            email: document.getElementById('editarEmail').value.trim(),
            telefone: document.getElementById('editarTelefone').value.trim(),
            plano: document.getElementById('editarPlano').value,
            id_instrutor: document.getElementById('editarInstrutor').value
        };

        if (!dados.nome || dados.idade <= 0 || !dados.email || !dados.plano) {
            alert('Preencha corretamente os campos obrigatórios.');
            return;
        }

        try {
            botaoSalvar.disabled = true;
            botaoSalvar.textContent = 'Salvando...';

            const resposta = await fetch(API_ALUNOS, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dados)
            });

            const resultado = await resposta.json();

            if (!resposta.ok) {
                throw new Error(resultado.erro || 'Erro ao atualizar aluno.');
            }

            alert(resultado.mensagem || 'Aluno atualizado com sucesso!');

            const elementoModal = document.getElementById('modalEditarAluno');
            const modalAberto = bootstrap.Modal.getInstance(elementoModal);

            if (modalAberto) {
                modalAberto.hide();
            }

            await carregarAlunos();

        } catch (erro) {
            console.error('Erro ao editar aluno:', erro);
            alert(erro.message);

        } finally {
            botaoSalvar.disabled = false;
            botaoSalvar.textContent = 'Salvar alterações';
        }
    });
});

function renderAlunos() {
    const tabela = document.getElementById('listaAlunos');

    if (!tabela) return;

    const campoBusca = document.getElementById('busca');

    const pesquisa = campoBusca
        ? campoBusca.value.toLowerCase().trim()
        : '';

    const alunosFiltrados = alunos.filter(aluno => {
        return (
            String(aluno.nome || '').toLowerCase().includes(pesquisa) ||
            String(aluno.email || '').toLowerCase().includes(pesquisa) ||
            String(aluno.telefone || '').toLowerCase().includes(pesquisa) ||
            String(aluno.plano || '').toLowerCase().includes(pesquisa) ||
            String(aluno.idade || '').toLowerCase().includes(pesquisa)
        );
    });

    tabela.innerHTML = alunosFiltrados.map(aluno => `
        <tr>
            <td>${esc(aluno.nome)}</td>
            <td>${esc(aluno.idade)}</td>
            <td>${esc(aluno.email || 'Não informado')}</td>
            <td>${esc(aluno.telefone || 'Não informado')}</td>
            <td>${esc(aluno.plano)}</td>
            <td>
                <span class="badge bg-success">Ativo</span>
            </td>

            ${window.PERFIL_USUARIO === 'admin' ? `
                <td>
                    <button
                        class="btn btn-sm btn-primary mb-1"
                        onclick="editarAluno(${Number(aluno.id)})">
                        <i class="fas fa-edit"></i> Editar
                    </button>

                    <button
                        class="btn btn-sm btn-danger"
                        onclick="excluirAluno(${Number(aluno.id)})">
                        <i class="fas fa-trash"></i> Excluir
                    </button>
                </td>
            ` : ''}
        </tr>
    `).join('') || `
        <tr>
            <td colspan="${window.PERFIL_USUARIO === 'admin' ? 7 : 6}" class="text-center">
                Nenhum aluno encontrado.
            </td>
        </tr>
    `;
}

function preencherAlunos(idSelect) {
    const select = document.getElementById(idSelect);

    if (!select) return;

    const valorAnterior = select.value;

    select.innerHTML =
        '<option value="">Selecione um aluno</option>' +
        alunos.map(aluno => `
            <option value="${esc(aluno.id)}">
                ${esc(aluno.nome)}
            </option>
        `).join('');

    select.value = valorAnterior;
}

function preencherInstrutores(idSelect) {
    const select = document.getElementById(idSelect);

    if (!select) return;

    const valorAnterior = select.value;

    select.innerHTML =
        '<option value="">Sem instrutor</option>' +
        instrutores.map(instrutor => `
            <option value="${Number(instrutor.id)}">
                ${esc(instrutor.nome)}
            </option>
        `).join('');

    select.value = valorAnterior;
}



async function addAluno(event) {
    event.preventDefault();

    const nome = document.getElementById('nome').value.trim();
    const idade = document.getElementById('idade').value;
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;
    const telefone = document.getElementById('telefone').value.trim();
    const plano = document.getElementById('plano').value;

    const campoInstrutor = document.getElementById('id_instrutor');
    const idInstrutor = campoInstrutor ? campoInstrutor.value : '';

    if (!nome || !idade || !email || !senha || !plano) {
        alert('Preencha todos os campos obrigatórios.');
        return;
    }

    if (senha.length < 8) {
        alert('A senha deve ter pelo menos 8 caracteres.');
        return;
    }

    try {
        const resposta = await fetch(API_ALUNOS, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nome,
                idade,
                email,
                senha,
                telefone,
                plano,
                id_instrutor: idInstrutor || null
            })
        });

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao cadastrar aluno.');
        }

        document.getElementById('formAluno').reset();

        await carregarAlunos();

        alert('Aluno e conta de acesso cadastrados com sucesso!');

    } catch (erro) {
        console.error('Erro ao cadastrar aluno:', erro);
        alert(erro.message);
    }
}



async function excluirAluno(id) {
    if (!confirm('Deseja realmente excluir este aluno?')) {
        return;
    }

    try {
        const resposta = await fetch(
            `${API_ALUNOS}?id=${encodeURIComponent(id)}`,
            { method: 'DELETE' }
        );

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao excluir aluno.');
        }

        await carregarAlunos();

        alert('Aluno excluído com sucesso!');

    } catch (erro) {
        console.error('Erro ao excluir aluno:', erro);
        alert(erro.message);
    }
}



async function carregarTreinos() {
    const resposta = await fetch(API_TREINOS);

    if (!resposta.ok) {
        throw new Error('Erro ao carregar os treinos.');
    }

    treinos = await resposta.json();

    renderDashboard();
    renderTreinos();
}

async function addTreino(event) {
    event.preventDefault();

    const aluno = document.getElementById('alunoTreino').value;
    const grupo = document.getElementById('grupo').value;
    const exercicio = document.getElementById('exercicio').value.trim();
    const series = document.getElementById('series').value;
    const repeticoes = document.getElementById('repeticoes').value.trim();
    const carga = document.getElementById('carga').value || 0;

    if (!aluno || !grupo || !exercicio || !series || !repeticoes) {
        alert('Preencha os campos obrigatórios e selecione um aluno.');
        return;
    }

    if (Number(series) < 1 || Number(carga) < 0 || !Number.isFinite(Number(carga))) {
        alert('Informe valores válidos para séries e carga.');
        return;
    }

    try {
        const resposta = await fetch(API_TREINOS, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                aluno,
                grupo,
                exercicio,
                series,
                repeticoes,
                carga
            })
        });

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao cadastrar treino.');
        }

        document.getElementById('formTreino').reset();

        await carregarTreinos();

        alert('Ficha de treino cadastrada com sucesso!');

    } catch (erro) {
        console.error('Erro ao cadastrar treino:', erro);
        alert(erro.message);
    }
}

function renderTreinos() {
    const tabela = document.getElementById('listaTreinos');

    if (!tabela) return;

    const ehAdmin = window.PERFIL_USUARIO === 'admin';

    console.log('Perfil recebido:', window.PERFIL_USUARIO);

    preencherAlunos('alunoTreino');

    tabela.innerHTML = treinos.map(treino => `
        <tr>
            <td>${esc(treino.aluno_nome || nomeAluno(treino.aluno))}</td>
            <td>${esc(treino.grupo)}</td>
            <td>${esc(treino.exercicio)}</td>
            <td>${esc(treino.series)}</td>
            <td>${esc(treino.repeticoes)}</td>
            <td>${esc(treino.carga || 'Não informada')} kg</td>
            ${ehAdmin ? `
                <td>
                    <button
                        class="btn btn-sm btn-danger"
                        onclick="excluirTreino(${Number(treino.id)})">
                        Excluir
                    </button>
                </td>
            ` : ''}
        </tr>
    `).join('') || `
        <tr>
            <td colspan="${ehAdmin ? 7 : 6}" class="text-center">
                Nenhum treino registrado.
            </td>
        </tr>
    `;
}

async function excluirTreino(id) {
    if (!confirm('Deseja excluir este treino?')) return;

    try {
        const resposta = await fetch(
            `${API_TREINOS}?id=${encodeURIComponent(id)}`,
            { method: 'DELETE' }
        );

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao excluir treino.');
        }

        await carregarTreinos();

        alert('Treino excluído com sucesso!');

    } catch (erro) {
        console.error('Erro ao excluir treino:', erro);
        alert(erro.message);
    }
}



async function carregarAvaliacoes() {
    try {
        const resposta = await fetch(API_AVALIACOES);

        if (!resposta.ok) {
            throw new Error('Erro ao carregar avaliações.');
        }

        avaliacoes = await resposta.json();

        renderAvaliacoes();
        renderDashboard();

        console.log('Avaliações carregadas:', avaliacoes);

    } catch (erro) {
        console.error('Erro ao carregar avaliações:', erro);
        throw erro;
    }
}

async function addAvaliacao(event) {
    event.preventDefault();

    const aluno = document.getElementById('alunoAvaliacao').value;
    const peso = document.getElementById('peso').value;
    const altura = document.getElementById('altura').value;
    const gordura = document.getElementById('gordura').value;

    if (!aluno || !peso || !altura) {
        alert('Preencha os campos obrigatórios.');
        return;
    }

    try {
        const resposta = await fetch(API_AVALIACOES, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                aluno,
                peso,
                altura,
                gordura
            })
        });

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao salvar avaliação.');
        }

        document.getElementById('formAvaliacao').reset();

        await carregarAvaliacoes();

        alert('Avaliação cadastrada com sucesso!');

    } catch (erro) {
        console.error('Erro ao salvar avaliação:', erro);
        alert(erro.message);
    }
}

function renderAvaliacoes() {
    const tabela = document.getElementById('listaAvaliacoes');

    if (!tabela) return;

    const ehAdmin = window.PERFIL_USUARIO === 'admin';

    preencherAlunos('alunoAvaliacao');

    tabela.innerHTML = avaliacoes.map(avaliacao => `
        <tr>
            <td>${esc(avaliacao.nome_aluno || nomeAluno(avaliacao.aluno))}</td>
            <td>${esc(avaliacao.data)}</td>
            <td>${esc(avaliacao.peso)} kg</td>
            <td>${esc(avaliacao.altura)} m</td>
            <td>${esc(avaliacao.imc)}</td>
            <td>
                ${avaliacao.gordura !== null && avaliacao.gordura !== ''
                    ? esc(avaliacao.gordura) + '%'
                    : 'Não informada'}
            </td>
            ${ehAdmin ? `
                <td>
                    <button
                        class="btn btn-sm btn-danger"
                        onclick="excluirAvaliacao(${Number(avaliacao.id)})">
                        Excluir
                    </button>
                </td>
            ` : ''}
        </tr>
    `).join('') || `
        <tr>
            <td colspan="${ehAdmin ? 7 : 6}" class="text-center">
                Nenhuma avaliação registrada.
            </td>
        </tr>
    `;
}

async function excluirAvaliacao(id) {
    if (!confirm('Deseja excluir esta avaliação?')) return;

    try {
        const resposta = await fetch(
            `${API_AVALIACOES}?id=${encodeURIComponent(id)}`,
            { method: 'DELETE' }
        );

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao excluir avaliação.');
        }

        await carregarAvaliacoes();

        alert('Avaliação excluída com sucesso!');

    } catch (erro) {
        console.error('Erro ao excluir avaliação:', erro);
        alert(erro.message);
    }
}



async function carregarInstrutores() {
    try {
        const resposta = await fetch(API_INSTRUTORES);

        if (!resposta.ok) {
            throw new Error('Erro ao carregar instrutores.');
        }

        instrutores = await resposta.json();

        preencherInstrutores('id_instrutor');

        renderInstrutores();
        renderDashboard();

        console.log('Instrutores carregados:', instrutores);

    } catch (erro) {
        console.error('Erro ao carregar instrutores:', erro);
        throw erro;
    }
}

function renderInstrutores() {
    const tabela = document.getElementById('listaInstrutores');

    if (!tabela) return;

    tabela.innerHTML = instrutores.map(instrutor => `
        <tr>
            <td>${esc(instrutor.nome)}</td>
            <td>${esc(instrutor.especialidade || 'Não informada')}</td>
            <td>
                <button
                    class="btn btn-sm btn-danger"
                    onclick="excluirInstrutor(${Number(instrutor.id)})">
                    Excluir
                </button>
            </td>
        </tr>
    `).join('') || `
        <tr>
            <td colspan="3" class="text-center">
                Nenhum instrutor cadastrado.
            </td>
        </tr>
    `;
}

async function addInstrutor(event) {
    event.preventDefault();

    const nome = document.getElementById('nomeInstrutor')?.value.trim();
    const especialidade = document.getElementById('especialidade')?.value.trim() || '';

    if (!nome) {
        alert('Informe o nome do instrutor.');
        return;
    }

    try {
        const resposta = await fetch(API_INSTRUTORES, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nome,
                especialidade
            })
        });

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao cadastrar instrutor.');
        }

        document.getElementById('formInstrutor').reset();

        await carregarInstrutores();

        alert('Instrutor cadastrado com sucesso!');

    } catch (erro) {
        console.error('Erro ao cadastrar instrutor:', erro);
        alert(erro.message);
    }
}

async function excluirInstrutor(id) {
    if (!confirm('Deseja realmente excluir este instrutor?')) return;

    try {
        const resposta = await fetch(
            `${API_INSTRUTORES}?id=${encodeURIComponent(id)}`,
            { method: 'DELETE' }
        );

        const resultado = await resposta.json();

        if (!resposta.ok) {
            throw new Error(resultado.erro || 'Erro ao excluir instrutor.');
        }

        await carregarInstrutores();

        alert('Instrutor excluído com sucesso!');

    } catch (erro) {
        console.error('Erro ao excluir instrutor:', erro);
        alert(erro.message);
    }
}



function renderRelatorios() {
    const totalAlunos = document.getElementById('relTotalAlunos');
    const totalTreinos = document.getElementById('relTotalTreinos');
    const totalInstrutores = document.getElementById('relTotalInstrutores');
    const totalAvaliacoes = document.getElementById('relTotalAvaliacoes');

    const resumo = document.getElementById('resumo');
    const historico = document.getElementById('relatorioAvaliacoes');

    if (totalAlunos) totalAlunos.textContent = alunos.length;
    if (totalTreinos) totalTreinos.textContent = treinos.length;
    if (totalInstrutores) totalInstrutores.textContent = instrutores.length;
    if (totalAvaliacoes) totalAvaliacoes.textContent = avaliacoes.length;

    if (resumo) {
        resumo.innerHTML = `
            Atualmente, a academia possui
            <strong>${alunos.length}</strong> aluno(s),
            <strong>${treinos.length}</strong> treino(s),
            <strong>${instrutores.length}</strong> instrutor(es)
            e <strong>${avaliacoes.length}</strong> avaliação(ões)
            física(s) cadastrada(s).
        `;
    }

    if (historico) {
        if (avaliacoes.length === 0) {
            historico.innerHTML = `
                <p class="text-muted">
                    Nenhuma avaliação física registrada.
                </p>
            `;
            return;
        }

        historico.innerHTML = `
            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-2">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Data</th>
                            <th>Peso</th>
                            <th>Altura</th>
                            <th>IMC</th>
                            <th>Gordura corporal</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${avaliacoes.map(avaliacao => `
                            <tr>
                                <td>
                                    ${esc(avaliacao.nome_aluno || nomeAluno(avaliacao.aluno))}
                                </td>
                                <td>${esc(avaliacao.data)}</td>
                                <td>${esc(avaliacao.peso)} kg</td>
                                <td>${esc(avaliacao.altura)} m</td>
                                <td>${esc(avaliacao.imc)}</td>
                                <td>
                                    ${avaliacao.gordura !== null && avaliacao.gordura !== ''
                                        ? esc(avaliacao.gordura) + '%'
                                        : 'Não informada'}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }
}



document.addEventListener('DOMContentLoaded', () => {
    console.log('FitControl iniciado.');

    renderDashboard();
    renderAlunos();
    renderInstrutores();
    renderRelatorios();

    carregarAlunos();
});
