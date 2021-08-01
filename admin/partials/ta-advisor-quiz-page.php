<div id="app" class="app">
    <div class="wrap">
        <header class="page-header mb-4">
            <h1 class="wp-heading-inline">TechArray Product Advisor</h1><button type="button" class="btn btn-sm btn-primary" v-on:click="toggleShowAddQuiz">{{ addQuizBtn.text }}</button>
        </header>
        <div class="add_quiz col-sm-12 col-md-4" id="add_quiz" v-show="showAddQuiz">
            <form method="POST" @submit="checkForm">
                <div class="form-group">
                    <p v-if="errors.length">
                        <b>Please correct the following error(s):</b>
                        <ul>
                        <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </p>
                </div>
                <div class="form-group">
                    <label for="quiz_name">Quiz Name</label>
                    <input class="form-control" type="text" name="quiz_name" id="quiz_name" v-model="quiz_name"/>
                </div>
                <input class="btn btn-primary btn-sm" type="submit" value="Submit">
            </form>
        </div>
        <div class="table-responsive" v-show="showQuizTable">
            <table class="table table-primary table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Quiz Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in quizes" :key="item.id">
                        <th scope="col">{{ index }}</th>
                        <td>{{ item.quiz_name }}</td>
                        <td><button type="button" class="btn btn-info me-2" :data-id="item.id">Edit</button><button type="button" class="btn btn-danger">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>  
</div>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            quizes: null,
            showAddQuiz: false,
            showQuizTable: true,
            addQuizBtn: {
                text: "Add New"
            },
            quiz_name: null,
            errors: [],
            message: 'Hello Vue!'
        },
        created () {
            // fetch the data when the view is created and the data is
            // already being observed
            this.fetchQuiz()
        },
        watch: {
            // call again the method if the route changes
            '$route': 'fetchQuiz'
        },
        methods: {
            async fetchQuiz(){
                var data = new FormData();
                data.append('action', 'ta_get_quizes');
                data.append('nonce', ajax_object.ta_advisor_nonce);

                const response = await fetch(ajax_object.ajax_url,{
                    method: 'POST',
                    body: data
                });
                this.quizes = await response.json();
            },
            toggleShowAddQuiz () {
                this.showAddQuiz = !this.showAddQuiz
                this.showQuizTable = !this.showQuizTable
                this.addQuizBtn.text = this.showAddQuiz ? "Cancel" : "Add New"
            },
            checkForm: function (e) {
                if (this.quiz_name) {
                    var data = new FormData();
                    data.append('action', 'ta_add_quiz');
                    data.append('nonce', ajax_object.ta_advisor_nonce);
                    data.append('quiz_name', this.quiz_name);

                    fetch(ajax_object.ajax_url,{
                        method: 'POST',
                        body: data
                    }).then(response =>{
                        if(response.ok){
                            this.fetchQuiz();
                            this.toggleShowAddQuiz();
                        }
                    });
                }

                this.errors = [];

                if (!this.quiz_name) {
                    this.errors.push('Enter valid quiz name.');
                }

                e.preventDefault();
            },
            editQuiz(){
                
            }
        }
    });
</script>