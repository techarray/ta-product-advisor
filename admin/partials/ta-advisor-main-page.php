<div id="app" class="app">
    <div class="wrap">
        <header class="page-header mb-4">
            <h1 class="wp-heading-inline">TechArray Product Advisor</h1><button type="button" class="btn btn-sm btn-primary" v-on:click="toggleShowAddQuiz">{{ addQuizBtn.text }}</button>
        </header>
        <div class="add_quiz col-sm-12 col-md-4" id="add_quiz" v-show="showAddQuiz">
            <form method="POST">
                <div class="form-group">
                    <label for="quiz_name">Quiz Name</label>
                    <input class="form-control" type="text" name="quiz_name" id="quiz_name" />
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
                        <td><button type="button" class="btn btn-info me-2">Edit</button><button type="button" class="btn btn-danger">Delete</button></td>
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
            quizes: <?php echo json_encode($data); ?>,
            showAddQuiz: false,
            showQuizTable: true,
            addQuizBtn: {
                text: "Add New"
            },
            message: 'Hello Vue!'
        },
        methods: {
            toggleShowAddQuiz () {
                this.showAddQuiz = !this.showAddQuiz
                this.showQuizTable = !this.showQuizTable
                this.addQuizBtn.text = this.showAddQuiz ? "Cancel" : "Add New"
            }
        }
    });
</script>