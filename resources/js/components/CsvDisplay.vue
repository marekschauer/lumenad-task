<template>
    <div>
        <h1 class="pb-8 text-center font-bold text-3xl">CSV Data</h1>
        <div v-if="error !== ''" class="alert alert-danger">
            <p>{{ error }}</p>
        </div>
        <div v-if="message !== ''" class="alert alert-success">
            <p>{{ message }}</p>
        </div>
        <div class="flex flex-row mb-5 justify-end">
            <a href="#" class="button" @click="createColumn()">
                <i class="fas fa-plus-square"></i>
                Add column
            </a>
            <a href="#" class="button" @click="showSaveDialog()">
                <i class="fas fa-save"></i>
                Save these data to database
            </a>
        </div>
        <div v-if="save_dialog_visible" class="flex flex-row mb-5 justify-end">
            <input type="text" placeholder="Enter the name of the dataset" v-model="dataset_name" class="w-full p-2">
            <a href="#" class="button" @click="save()">
                Confirm
            </a>
        </div>
        <div class="mt-2 overflow-x-scroll">
            <table class="max-w-5xl mx-auto table-auto">
                <thead class="justify-between">
                    <tr class="bg-green-600">
                        <th v-for="header_col in csv_header" v-bind:key="header_col" class="px-4 border border-white md:px-8 py-2">
                            <span class="text-gray-100 font-semibold">
                                {{ header_col }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-200">
                    
                
                    <tr  v-for="row in csv_data" class="bg-white border border-gray-200">
                        
                            <td v-for="col in row" v-bind:key="col" class="px-4 sm:border border-gray-200 md:px-8 py-2">
                                <span>{{ col }}</span>
                            </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        
        props: [
            'header',
            'body',
        ],
        
        data: function () {
            return {
                message: "",
                error: "",
                csv_header: null,
                csv_data: null,
                save_dialog_visible: false,
                dataset_name: ""
            }
        },
        
        created() {
            this.csv_header = JSON.parse(this.header)
            this.csv_data = JSON.parse(this.body)

            if (!this.csv_header)
            console.log(this.header, this.csv_data)
        },
        
        mounted() {
            console.log("Example component mounted");
        },

        methods: {
            createColumn() {
                axios.post('/csv/add-column', {
                    csv_header: this.csv_header,
                    csv_body: this.csv_data
                })
                .then((response) => {
                    let recieved_data = response.data
                    this.csv_header = recieved_data.header
                    this.csv_data = recieved_data.body
                })
                .catch(err => this.error = err)
            },
            showSaveDialog() {
                this.save_dialog_visible = !(this.save_dialog_visible)
                this.cleanState()
            },
            save() {
                axios.post('/csv/save', {
                    name: this.dataset_name,
                    csv_header: this.csv_header,
                    csv_body: this.csv_data
                })
                .then((response) => {
                    this.message = response.data.message
                })
                .catch(err => this.error = err)
            },
            cleanState() {
                this.error = ""
                this.message = ""
                this.dataset_name = ""
            }
        }
        
    };
</script>
