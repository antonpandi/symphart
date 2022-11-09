const articles = document.getElementById('articles');

console.log("Articles:",articles)
if(articles){
    articles.addEventListener('click', e =>{
        if(e.target.className === 'btn btn-danger delete-article'){
            console.log("Button Was Clicked", e);
            if(confirm('Are you shure')){
                const id = e.target.dataset.id
                fetch(`/article/delete/${id}`,{
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
        else{
            console.log("Target Was Not A Button");
        }
    });
    console.log("There was articles")
}
else{
    console.log("There were no articles")
}