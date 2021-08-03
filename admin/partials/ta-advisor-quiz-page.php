<?php 
$save_icon = $this->get_svg("save");
$close_icon = $this->get_svg("close"); 
?>

<div id="app" class="app">
    
    <div class="container">
        <header class="header">
            <h1 class="wp-heading-inline">Quizes</h1><button type="button" class="icon_button" v-on:click="toggleShowAddQuiz"><?php echo $this->get_svg("add"); ?></button>
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
                    <label for="quiz_name">Name</label>
                    <input class="form-control" type="text" name="quiz_name" id="quiz_name" v-model="quiz_name"/>
                </div>
                <div class="icon_button_bar">
                    <button type="submit" class="icon_button"><?php echo $save_icon; ?></button>
                    <button type="button" class="icon_button" v-on:click="toggleShowAddQuiz"><?php echo $close_icon; ?></button>
                </div>
            </form>
        </div>
        <div class="quizes" v-show="showQuizTable">
            <div class="quiz" v-for="(item, index) in quizes" :key="item.id">
                <h4 class="title">{{ item.quiz_name }}</h4>
                <ul class="actions">
                    <li><button type="button" class="flat_button" :data-id="item.id"><?php echo $this->get_svg("edit"); ?></button></li>
                    <li><button type="button" class="flat_button"><?php echo $this->get_svg("delete"); ?></button></li>
                </ul>
            </div>
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
            quizBtn: {
                text: "add"
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
                this.quizBtn.text = this.showAddQuiz ? "close" : "add"
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