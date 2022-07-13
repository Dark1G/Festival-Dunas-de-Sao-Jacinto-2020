<template>
	<div>
		<form v-if="showform" @submit.prevent="submitData">
			<div class="form-group">
				<label>Nome Completo *</label>
				<input type="text" class="form-control" v-model="form.nome" required />
				<div v-if="errors.nome"><span v-for="msg in errors.nome">{{ msg }}</span></div>
			</div>
			<div class="form-group">
				<div class="form-row">
					<div class="col">
						<label>Data de Nascimento *</label>
						<input type="date" min="1956-08-20" max="2015-08-20" class="form-control" v-model="form.data_nascimento" required />
						<div v-if="errors.data_nascimento"><span v-for="(msg,i) in errors.data_nascimento" :key="i">{{ msg }}</span></div>
					</div>
					<div class="col">
						<label>BI/CC ou Passaporte *</label>
						<input type="text" class="form-control" v-model="form.documento_id" required />
						<div v-if="errors.documento_id"><span v-for="msg in errors.documento_id">{{ msg }}</span></div>
					</div>	
				</div>
			</div>
			<div class="form-group">
				<label>E-mail *</label>
				<input type="email" class="form-control" v-model="form.email" required />
				<div v-if="errors.email"><span v-for="msg in errors.email">{{ msg }}</span></div>
			</div>
			<div class="form-group">
				<label>Morada *</label>
				<textarea rows="4" class="form-control" v-model="form.morada" required></textarea>
				<div v-if="errors.morada"><span v-for="msg in errors.morada">{{ msg }}</span></div>
			</div>
			<template v-if="evento=='canoagem'">
				<div class="row">
					<div class="col mt-4 mb-3">
						<h4 class="alert-heading mb-0">Dados do Acompanhante</h4>
						<small>* Preenchimento Obrigatório</small>
					</div>
				</div>
				<div class="form-group">
					<label>Nome Completo *</label>
					<input type="text" class="form-control" v-model="form.nome_acompanhante" required />
					<div v-if="errors.nome_acompanhante"><span v-for="msg in errors.nome_acompanhante">{{ msg }}</span></div>
				</div>
				<div class="form-group">
					<div class="form-row">
						<div class="col">
							<label>Data de Nascimento *</label>
							<input type="date" min="1956-08-20" max="2015-08-20" class="form-control" v-model="form.data_nascimento_acompanhante" required />
							<div v-if="errors.data_nascimento_acompanhante"><span v-for="(msg,i) in errors.data_nascimento_acompanhante" :key="i">{{ msg }}</span></div>
						</div>
						<div class="col">
							<label>BI/CC ou Passaporte *</label>
							<input type="text" class="form-control" v-model="form.documento_id_acompanhante" required />
							<div v-if="errors.documento_id_acompanhante">
								<span v-for="msg in errors.documento_id_acompanhante">{{ msg }}</span>
							</div>
						</div>	
					</div>
				</div>
				<div class="form-group">
					<label>Morada *</label>
					<textarea rows="4" class="form-control" v-model="form.morada_acompanhante" required></textarea>
					<div v-if="errors.morada_acompanhante"><span v-for="msg in errors.morada_acompanhante">{{ msg }}</span></div>
				</div>
			</template>

			<template v-if="evento=='sup'">
				<div class="form-group">
				<label>Leva criança? (Máximo 1 e até 7 anos de idade) *</label>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" :value="true" v-model="form.crianca">
					<label class="form-check-label" for="exampleRadios1">
						Sim
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" :value="false" v-model="form.crianca">
					<label class="form-check-label" for="exampleRadios2">
						Não
					</label>
				</div>
			</div>
			</template>

			<template v-if="form.crianca">
				<div class="row">
					<div class="col mt-4 mb-3">
						<h4 class="alert-heading mb-0">Dados da Criança</h4>
						<small>* Preenchimento Obrigatório</small>
					</div>
				</div>
				<div class="form-group">
					<label>Nome Completo *</label>
					<input type="text" class="form-control" v-model="form.nome_acompanhante" required />
					<div v-if="errors.nome_acompanhante"><span v-for="msg in errors.nome_acompanhante">{{ msg }}</span></div>
				</div>
				<div class="form-group">
					<div class="form-row">
						<div class="col">
							<label>Data de Nascimento *</label>
							<input type="date" min="2013-08-20" max="2016-08-20" class="form-control" v-model="form.data_nascimento_acompanhante" required />
							<div v-if="errors.data_nascimento_acompanhante"><span v-for="(msg,i) in errors.data_nascimento_acompanhante" :key="i">{{ msg }}</span></div>
						</div>
						<div class="col">
							<label>BI/CC ou Passaporte *</label>
							<input type="text" class="form-control" v-model="form.documento_id_acompanhante" required />
							<div v-if="errors.documento_id_acompanhante">
								<span v-for="msg in errors.documento_id_acompanhante">{{ msg }}</span>
							</div>
						</div>	
					</div>
				</div>
			</template>
			<div class="form-group">
				<label>Sessão *</label>
				<select class="form-control" v-model="form.sessao" required>
					<option value="" disabled>Selecione uma das sessões disponiveis</option>
					<option :class="!sessao.valid?'text-danger':''" v-for="sessao in listHours" :value="sessao.id" :disabled="!sessao.valid">{{ sessao.horas }} 
						<span  v-if="!sessao.valid">
							- Lotação esgotada!
						</span>
					</option>
				</select>
			</div>
			<div class="form-group">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" v-model="form.termos" id="defaultCheck1" required>
					<label class="form-check-label" for="defaultCheck1">
						Aceito os 
						<a href="#" data-toggle="modal" data-target=".bd-example-modal-lg">
							<b>Termos de Participação</b>
						</a>
					</label>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">
						Declaro que tomei conhecimento das condições de participação nas atividades desportivas
						inseridas no Festiva Dunas de São Jacinto 2020
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Será considerado um formulário de inscrição por pessoa.</p>
					<p>Cada pessoa poderá inscrever-se uma vez em cada atividade.</p>
					<p>Solicita-se o preenchimento dos seus dados pessoais e a indicação da(s) atividade(s) em que se
					pretende inscrever.</p>
					<p>A participação na atividade só será permitida se apresentado o documento de identificação do
					participante que foi indicado no momento da inscrição.</p>
					<p>O participante declara que não possui qualquer contraindicação para a prática desportiva, bem
					como está familiarizado com o meio aquático.</p>
					<p>O participante declara que tem conhecimento e cumprirá as normas de distanciamento social
					decretadas pela DGS bem como a restantes recomendações.</p>
					<p>O participante deve trazer equipamento e calçado apropriado a cada uma das atividades.</p>
					<p><b>PROTEÇÃO DE DADOS:</b> O signatário autoriza a recolha e o tratamento dos seus dados pessoais
					pelo Município de Aveiro que os utilizará exclusivamente para a gestão das inscrições nas
					atividades desportivas integradas na programação do Festival Dunas de São Jacinto 2020.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-primary" @click.prevent="termos">Aceito</button>
				</div>
				</div>
			</div>
		</div>

			<div class="alert alert-danger" role="alert" v-if="errorMessage">
			  {{ errorMessage }}
			</div>

			<button class="btn btn-primary" type="button" disabled v-if="this.loading">
			  <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
			  Loading...
			</button>
			<button type="submit" class="btn btn-primary mb-2" v-else>Inscrever</button>


		</form>
		<template v-if="!showform">
			<div class="alert alert-success" role="alert" v-if="successMessage">
				{{ successMessage }}
			</div>
		</template>
	</div>
</template>

<script>
	const formEvento = {
		nome: '',
		data_nascimento: '',
		documento_id: '',
		email: '',
		morada: '',
		sessao: '',
		nome_acompanhante: '',
		data_nascimento_acompanhante: '',
		documento_id_acompanhante: '',
		morada_acompanhante: '',
		crianca: false,
		termos: false
	};
	
	const formErrors = {
		nome: null,
		data_nascimento: null,
		documento_id: null,
		email: null,
		morada: null,
		sessao: null,
		email: null,
		nome_acompanhante: null,
		data_nascimento_acompanhante: null,
		documento_id_acompanhante: null,
		morada_acompanhante: null
	};

	export default {
		props: {
			listHours:{
				required: true
			},
			counter: {
				required: true,
				type: Number
			},
			evento:{
				required: true,
			},
			route: {
				required: true
			}
		},
		data() {
			return {
				form: {
					crianca: false,
					termos: false
				},
				successMessage: null,
				errorMessage: null,
				loading: false,
				errors: {},
				showModal: false,
				showform: true
			}
		},
		created() {
			this.clearForm()
		},
		methods: {
			submitData() {
				this.loading = true
				axios.post(this.route, this.form)
					.then(({ data }) => {
						this.loading = false

						if (data.error) {
							this.errorMessage = data.mensagem
							return;
						}

						this.successMessage = data.mensagem
						this.clearForm()
						this.showform = false

					})
					.catch((err) => {
						this.loading = false 
						console.warn(err)
						if (err.response.data.errors) {
							this.errors = err.response.data.errors;
						}
					})
			},
			clearForm() {
				this.form = Object.assign(this.form, formEvento)
				this.errors = Object.assign(this.errors, formErrors)
				this.errorMessage = null
			},
			termos() {
				this.form.termos=true
				$('.bd-example-modal-lg').modal('hide')
			}
		}
	}

</script>