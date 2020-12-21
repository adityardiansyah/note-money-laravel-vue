<template>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <router-link :to="{ name: 'transaction.create'}" class="btn btn-primary btn-sm rounded shadow mb-3">Create</router-link>
                    </div>
                    <div class="col-6">
                        <input type="text" v-model="keyword" class="form-control float-right" placeholder="Search ..." @keyup="submitSearch">
                    </div>
                </div>
                <div class="card rounded shadow">
                    <div class="card-header">
                        Transaction List
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(transaction, index) in transactions.data" :key="index">
                                    <td>{{ transaction.title }}</td>
                                    <td>{{ transaction.amount }}</td>
                                    <td>{{ transaction.type }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <router-link 
                                                :to="{ name: 'transaction.edit', 
                                                params:{id: transaction.id}}" class="btn btn-sm btn-outline-info">Edit</router-link>
                                            <button class="btn btn-sm btn-outline-danger" @click.prevent="destroy(transaction.id, index)">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row justify-content-center">
                            <div class="col-5">
                                <button class="btn btn-primary" @click="getResult(pagination.prev_page_url)"
                                        :disabled="!pagination.prev_page_url">
                                    Previous
                                </button>
                                <span class="m-4">Page {{pagination.current_page}} of {{pagination.last_page}}</span>
                                <button class="btn btn-primary" @click="getResult(pagination.next_page_url)"
                                        :disabled="!pagination.next_page_url">Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { onMounted, ref, toRefs } from 'vue'

export default {
    mounted() {
        this.fetchData()
    },
    data: () => ({
        keyword: "",
        transactions: [],
        pagination: {}
    }),
    created(){
        this.getResult();
    },
    methods: {
        getResult: function(page_url){
            this.fetchData(this.keyword, page_url)
        },
        submitSearch : function(){
            this.fetchData(this.keyword)
        },
        fetchData: function(query = '', page_url){
            page_url = page_url || 'http://127.0.0.1:8000/api/transaction'
            axios.get(page_url,{
                params: {
                    search: query
                }
            })
            .then((result) => {
                this.makePagination(result.data.data)
                this.transactions = result.data.data
            }).catch((err) => {
                console.log(err);
            })
        },
        makePagination: function(data){
            let pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url
            }
            this.pagination = pagination
            // vm.$set('pagination', pagination)
        },
        destroy: function(id, index){
            axios.delete(
                'http://127.0.0.1:8000/api/transaction/'+id
            )
            .then(() => {
                this.transactions.data.splice(index, 1)
            }).catch((err) => {
                console.log(err.response.data);
            });
        }
    }
}
</script>