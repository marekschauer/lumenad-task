<template>
    <div>
        <input type="text" v-model="queryString" @keyup="search()">

        <div v-if="searched.length" class="pt-2 w-3/4 m-auto">
            <ul class="block">
                <li v-for="result in searched" v-bind:key="result.id" class="p-2 text-left hover:bg-gray-200">
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
                if (this.queryString == '') {
                    this.searched = [];
                    return;
                }

                axios.get('/csv/search', {
                    params: {
                        'query': this.queryString
                    }
                })
                .then(response => this.searched = response.data.imported_data)
                .catch(err => console.log(err))
            }
        }
        
    };
</script>
