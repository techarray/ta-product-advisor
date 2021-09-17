<?php 
$save_icon = $this->get_svg("save");
$close_icon = $this->get_svg("close"); 
?>

<div id="app" class="app">   
    <nav class="app-navigation">
        <ul class="app-navigation-menu">
            <li><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
            <li><router-link :to="{ name: 'quizes' }">Quizes</router-link></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Info</a></li>
        </ul>
    </nav>
    <div class="app-content">
        <router-view></router-view>
    </div> 
</div>

<script>
    const Dashboard = Vue.component('quiz-page',{
        template: `<div class="quiz-page">
            <header class="header">
                <h1 class="wp-heading-inline">Dashboard</h1>
            </header>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex blanditiis minus ratione natus mollitia nulla veritatis facere, quis rem impedit eum excepturi aliquam odio tempore odit vero quos culpa autem.</p>
        </div>`
    });
    const QuizPage = Vue.component('quiz-page',{
        data: function(){
            return {
                quizes: null,
                showAddQuiz: false,
                showQuizTable: true,
            }
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
        },
        template: `<div class="quiz-page">
            <header class="header">
                <h1 class="wp-heading-inline">Quizes</h1><button type="button" class="icon_button" v-on:click="toggleShowAddQuiz"><?php echo $this->get_svg("add"); ?></button>
            </header>
            <div class="add_quiz col-sm-12 col-md-4" id="add_quiz" v-show="showAddQuiz">
                <add-quiz></add-quiz>
            </div>
            <div class="quizes" v-show="showQuizTable">
                <quiz-item
                v-for="item in quizes"
                v-bind:quiz="item"
                v-bind:key="item.id"
                ></quiz-item>
            </div>
        </div>`
    });
    Vue.component('add-quiz', {
        data: function(){
            return {
                quiz_name: null,
                errors: [],
                quizBtn: {
                    text: "add"
                },
            }
        },
        methods: {
            toggleShowAddQuiz () {
                this.$parent.showAddQuiz = !this.$parent.showAddQuiz
                this.$parent.showQuizTable = !this.$parent.showQuizTable
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
                            // this.toggleShowAddQuiz();
                        }
                    });
                }

                this.errors = [];

                if (!this.quiz_name) {
                    this.errors.push('Enter valid quiz name.');
                }

                e.preventDefault();
            },
        },
        template: `<form method="POST" @submit="checkForm()">
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
                    <button type="button" class="icon_button" v-on:click="toggleShowAddQuiz()"><?php echo $close_icon; ?></button>
                </div>
            </form>`
    });
    Vue.component('quiz-item', {
        props: ['quiz'],
        template: `<div class="quiz">
                <h4 class="title">{{ quiz.quiz_name }}</h4>
                <ul class="actions">
                    <li><button type="button" class="flat_button" :data-id="quiz.id"><?php echo $this->get_svg("edit"); ?></button></li>
                    <li><button type="button" class="flat_button"><?php echo $this->get_svg("delete"); ?></button></li>
                </ul>
            </div>`
    });
    /**
     * App routes
     */
    const routes = [
        { name: 'dashboard', path:'/dashboard', component: Dashboard },
        { name: 'quizes', path:'/quizes', component: QuizPage }
    ];
    /**
     * App Router
     */
    const router = new VueRouter({
    routes // short for `routes: routes`
    });
    var app = new Vue({router}).$mount('#app');
</script>