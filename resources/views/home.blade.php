@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">Articles</div>
                <div class="card-body">
                        <div class="card card-body mb-2" v-for="article in articles" v-bind:key="article.id">
                        <h3>@{{ article.title }}</h3>
                        <p>@{{ article.body }}</p>
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
                article_id: '',
                pagination: {},
                edit: false
            };
        },
        
        created() {
            this.fetchArticles();
        },
        
        methods: {
            
            fetchArticles() {
                fetch('/api/articles')
                .then(res => res.json())
                .then(res => {
                    this.articles = res.data;
                });
            }
        }
    })
</script>
@endsection
