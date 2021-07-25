<template>
    <div>
        <input type="text" v-model="queryString" @keyup="search()">

        <div v-if="searched.length" class="pt-2">
            <ul>
                <li v-for="result in searched" v-bind:key="result.id" class="p-2">
                    <a v-bind:href="'/csv/' + result.id">
                        {{ result.name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        
        data: function () {
            return {
                queryString: '',
                searched: []
            }
        },
        
        created() {
            return
        },

        methods: {
            search() {
               axios.get('/csv/search')
               .then(response => this.searched = response.data.imported_data)
               .catch(err => console.log(err))
            }
        }
        
    };
</script>
