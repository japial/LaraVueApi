@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">Articles</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <form @submit.prevent="saveArticle" class="mb-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Title" v-model="article.title">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Body" v-model="article.body"></textarea>
                                </div>
                                <button type="submit" class="btn btn-secondary btn-block">Save</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mx-auto">
                            
                            <ul class="pagination">
                                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchArticles(pagination.prev_page_url)">Previous</a></li>
                                
                                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page @{{ pagination.current_page }} of @{{ pagination.last_page }}</a></li>
                                
                                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchArticles(pagination.next_page_url)">Next</a></li>
                            </ul>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-body mb-2" v-for="article in articles" v-bind:key="article.id">
                                <h3>@{{ article.title }}</h3>
                                <p>@{{ article.body }}</p>
                                <hr>
                                <div class="button-group text-center">
                                    <button @click="editArticle(article)" class="btn btn-warning">Edit</button>
                                    <button @click="deleteArticle(article.id)" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var app = new Vue({
        el: '#vueApp',
        data() {
            return {
                articles: [],
                article: {
                    id: '',
                    title: '',
                    body: ''
                },
                pagination: {},
                edit: false
            };
        },
        
        created() {
            this.fetchArticles();
        },
        
        methods: {
            fetchArticles(page_url) {
                let vm = this;
                page_url = page_url || '/api/articles';
                fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.articles = res.data;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                
                this.pagination = pagination;
            },
            deleteArticle(id) {
                if (confirm('Are You Sure?')) {
                    fetch(`api/article/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Article Removed');
                        this.fetchArticles();
                    })
                    .catch(err => console.log(err));
                }
            },
            saveArticle() {
                if (this.edit === false) {
                    // Add
                    fetch('api/article', {
                        method: 'post',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.article.title = '';
                        this.article.body = '';
                        alert('Article Added');
                        this.fetchArticles();
                    })
                    .catch(err => console.log(err));
                } else {
                    // Update
                    fetch('api/article/'+this.article.id, {
                        method: 'put',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.article.title = '';
                        this.article.body = '';
                        alert('Article Updated');
                        this.fetchArticles();
                    })
                    .catch(err => console.log(err));
                }
            },
            editArticle(article) {
                this.edit = true;
                this.article.id = article.id;
                this.article.title = article.title;
                this.article.body = article.body;
            }
        }
    })
</script>
@endsection
