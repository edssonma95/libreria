<template>
    <div>
        <div class="booksContainer" v-if="context == 'admin'">
            <div class="headerBooksContainer">
                <h2 class="tittle"><i class="fas fa-book"></i> Listado de libros</h2>
                <el-button class="btnAdd" size="mini" type="warning" data-toggle="modal" data-target="#newBookModal">
                    <i class="fas fa-plus"></i> Agregar libro
                </el-button>
            </div>
            <div class="bodyBooksContainer" v-loading="loadingBookList">
                <el-select
                    title="Buscar libro, autor o editorial..."
                    v-model="textBoxFilter"
                    filterable
                    clearable
                    remote
                    style="width: 100%;"
                    placeholder="Buscar libro, autor o editorial..."
                    :remote-method="getSuggestions"
                    :loading="loadingSuggestions"
                    @change="passTextValue">
                    <el-option
                        v-for="item in suggestions"
                        :key="item.id"
                        :label="item.name"
                        :value="item">
                        <span style="float: left">{{ item.name }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.type }}</span>
                    </el-option>
                </el-select>
                <el-table
                    :data="books"
                    style="width: 100%; max-height:420px;">
                    <el-table-column  
                    label="Portada">
                        <template slot-scope="object">
                            <img :src="'../storage/app/' + object.row.cover" :alt="object.row.name" :title="object.row.name" style="width:50%;">
                        </template>
                    </el-table-column>
                    <el-table-column               
                    prop="name"
                    label="Nombre">
                    </el-table-column>
                    <el-table-column
                    prop="description"
                    label="Descripcion">
                        <template slot-scope="object">
                            <div style="max-height:80px; overflow-y:auto; max-width:100%;">
                                   {{object.row.description}} 
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                    label="Autor">
                        <template slot-scope="object">
                                {{object.row.author.name}}
                        </template>
                    </el-table-column>
                    <el-table-column
                    label="Editorial">
                        <template slot-scope="object">
                                {{object.row.editorial.name}}
                        </template>
                    </el-table-column>
                    <el-table-column
                    label="Stock">
                         <template slot-scope="object">
                                {{object.row.inventory.quantity}}
                        </template>
                    </el-table-column>
                    <el-table-column
                    label="Lanzamiento">
                        <template slot-scope="object">
                                {{object.row.relase_date}}
                        </template>
                    </el-table-column>
                    <el-table-column
                    fixed="right"
                    label="Acciones">
                    <template slot-scope="object">
                        <!-- <el-button type="text" size="small" title="Ver"><i class="fas fa-eye"></i></el-button> -->
                        <el-button type="text" size="small" title="Editar" data-toggle="modal" data-target="#newBookModal"
                         @click="handleEditBook(object.row)"><i class="fas fa-pencil-alt"></i></el-button>
                        <el-button type="text" size="small" title="Eliminar" @click="deleteBook(object.row)"><i class="fas fa-trash-alt"></i></el-button>
                    </template>
                    </el-table-column>
                </el-table>
                 <el-pagination
                  style="display:flex; float:right;"
                  :page-size="size"
                  :current-page="currentPage"
                  layout="prev, pager, next"
                  @current-change="handleCurrentChange"
                  :total="total">
			    </el-pagination>  
            </div>
        </div>
        <div class="booksContainerPublic" v-else>
             <el-select
                    title="Buscar libro, autor o editorial..."
                    v-model="textBoxFilter"
                    filterable
                    clearable
                    remote
                    style="width: 100%;"
                    placeholder="Buscar libro, autor o editorial..."
                    :remote-method="getSuggestions"
                    :loading="loadingSuggestions"
                    @change="passTextValue">
                    <el-option
                        v-for="item in suggestions"
                        :key="item.id"
                        :label="item.name"
                        :value="item">
                        <span style="float: left">{{ item.name }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.type }}</span>
                    </el-option>
                </el-select>

                <div class="booksInCards">               
                        <div class="book" v-for="book in books" :key="book.id" @click="detail(book)" title="Ver detalle">
                            <img :src="'../storage/app/' + book.cover" :alt="book.name" :title="book.name" class="bookCover">
                        </div>
                </div>

        <!-- detail -->
        <el-dialog
            :title="bookDetails.name"
            :visible.sync="verDetalle"
            width="30%">
            <p class="description">{{bookDetails.description}}</p>
            <span >Autor: <el-tag type="warning" effect="dark" size="small">{{bookDetails.author}}</el-tag> </span> <hr>
            <span >Editorial: <el-tag type="warning" effect="dark" size="small">{{bookDetails.editorial}}</el-tag> </span> <hr>
            <span >Disponibles: <el-tag type="warning" effect="dark" size="small">{{bookDetails.inventory}}</el-tag> </span>

            <span slot="footer" class="dialog-footer">
                <el-button @click="verDetalle = false">Cerrar</el-button>
            </span>
        </el-dialog>
        </div>
       <!-- New Book Modal -->
            <div class="modal fade" id="newBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar libro a inventario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-loading="savingNewBook">
                        <input type="file" id="cover" @change="handlePicture()" style="width:100%; margin-bottom:10px;">
                        <el-input placeholder="Nombre" v-model="newBookData.name" style="margin-bottom:10px;"></el-input>
                        <el-input placeholder="Descripción" v-model="newBookData.description" style="margin-bottom:10px;"></el-input>
                        <el-date-picker
                            style="width:100%; margin-bottom:10px;"
                            v-model="newBookData.relase_date"
                            type="date"
                            placeholder="Fecha de lanzamiento"
                            format="yyyy/MM/dd"
                            value-format="yyyy/MM/dd">
                        </el-date-picker>
                        <el-select v-model="newBookData.author_id" placeholder="Seleccionar autor" filterable 
                        style="width:100%; margin-bottom:10px;">
                            <el-option
                            v-for="item in authors"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                            </el-option>
                        </el-select>
                        <el-select v-model="newBookData.editorial_id" placeholder="Seleccionar editorial" filterable 
                        style="width:100%; margin-bottom:10px;">
                            <el-option
                            v-for="item in editorials"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                            </el-option>
                        </el-select>
                        <el-input-number v-model="newBookData.quantity" :step="1" step-strictly  style="width:100%; margin-bottom:10px;">
                        </el-input-number>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="saveBook()">Guardar libro</button>
                    </div>
                    </div>
                </div>
            </div>
    </div>
</template>
<script>
export default {
    props:["context"],
    data() {
        return {
            books:[],
            editorials:[],
            authors:[],
            baseUrl: "/libreria/public/",
            newBook: new FormData(),
            newBookData:{
                name: null,
                description:null,
                relase_date:null,
                author_id:null,
                editorial_id:null,
                quantity: null
            },
            savingNewBook: false,
            loadingBookList: false,
            editingBook:false,
            //paginador
            size: this.context == "admin" ? 3 : 9,
            total: null,
            currentPage: 1,
            query: null,
            //filter
            textBoxFilter:null,
            loadingSuggestions: false,
            suggestions: [],
            verDetalle: false,
            bookDetails: {
                name: null,
                description: null,
                author: null,
                editorial: null,
                relase_date: null,
                inventory: null
            }
        }
    },
    mounted() {
        this.loadBooks();
        this.loadDataForNewBook();
    },
    methods: {
        getEditorials()
        {
            axios.get(this.baseUrl + 'getEditorials').then(response =>{
                this.editorials = response.data;
            });
        },
        getAuthors()
        {
            axios.get(this.baseUrl + 'getAuthors').then(response =>{
                this.authors = response.data;
            });
        },
        loadDataForNewBook()
        {
            this.getEditorials();
            this.getAuthors();
        },
        saveBook()
        {
            this.savingNewBook = true;
            this.newBook.delete('data');
            this.newBook.delete('context');

            this.newBook.append('data', JSON.stringify(this.newBookData));
            this.newBook.append('context', this.editingBook == true ?  "edit" : "add");
            
            axios.post('saveBook', this.newBook ,
                {headers: {'Content-Type': 'multipart/form-data'}}
                )
                .then(response => {
                    this.savingNewBook = false;

                    if(response.data.status == 200)
                    {

                        
                        this.newBook = new FormData();
                        this.newBookData.name = null;
                        this.newBookData.description = null;
                        this.newBookData.relase_date = null;
                        this.newBookData.author_id = null;
                        this.newBookData.editorial_id = null;
                        this.newBookData.quantity = null;

                        this.loadBooks();

                        this.$notify({
                            title: 'Éxito',
                            message: response.data.message,
                            type: 'success'
                        });

                        this.editingBook = false;
                        $("#newBookModal").modal('hide');
                    }
                    else
                    {
                        this.$notify.error({
                            title: 'Error',
                            message: response.data.error
                        });
                    }

                }).catch(error => {

            });
        },
        loadBooks()
        {
            this.loadingBookList = true;
            axios.get(this.baseUrl + "loadBooks", {
                params:{
                    size : this.size,
                    currentPage : this.currentPage,
                    query: this.query
                }
            }).then(response =>{
                this.books = Object.values(response.data.books);
                this.total = response.data.total;
                this.loadingBookList = false;
            });
        },
        deleteBook(book)
        {
            this.$confirm('¿Borrar el libro "'+book.name+'" ?', 'Borrar libro', {
            confirmButtonText: 'Borrar',
            cancelButtonText: 'Cancelar',
            type: 'warning'
            }).then(() => {
                axios.delete("deleteBook/" + book.id).then(response =>{
                     this.$notify({
                            title: 'Éxito',
                            message: 'El libro "' + book.name + '" fue borrado.',
                            type: 'success'
                        });
                    this.loadBooks();
                });
            }).catch(() => {
                        
            });
        },
        handlePicture()
        {
             this.newBook.append("cover", $("#cover").prop('files')[0]);
        },
        handleEditBook(book)
        {
            this.newBookData = book;
            this.newBookData.quantity = book.inventory.quantity;
            this.newBookData.relase_date = new Date(book.relase_date);
            this.editingBook = true;
        },
        handleCurrentChange(val) 
        {
            this.currentPage = val;
            this.loadBooks();
        },
        //filter
         getSuggestions(queryString, cb)
        {
            this.loadingSuggestions = true;
            axios.get(this.baseUrl + "textFilter/" + queryString).then(response =>{
                this.suggestions = response.data;
                this.loadingSuggestions = false;
            });
        },
        passTextValue()
        {
            this.query = this.textBoxFilter;
            this.loadBooks();
        },
        detail(book)
        {
            this.verDetalle = true;
            this.bookDetails = book;
            this.bookDetails.author = book.author.name;
            this.bookDetails.editorial = book.editorial.name;
            this.bookDetails.inventory = book.inventory.quantity;
        }

    },      
    watch: {
 
    },
}
</script>
<style>
.booksContainer
{
    width:100%;
    height:530px;
    border:1px solid #d9d9d9;
    border-radius: 8px;
    -webkit-box-shadow: 1px 2px 5px -2px rgba(0,0,0,0.75);
    -moz-box-shadow: 1px 2px 5px -2px rgba(0,0,0,0.75);
    box-shadow: 1px 2px 5px -2px rgba(0,0,0,0.75);
    border-top: 2px solid #F4AE3B;
    margin-bottom: 20px;
}
.headerBooksContainer
{
    margin:5px;
    padding:5px;
    width:100%;
    height: auto;
    display: flow-root;
}
.tittle
{
    float: left;
    font-size: 25px;
}
.btnAdd
{
    float: right;
    margin-right: 10px;
}
.bodyBooksContainer
{
    width:100%;
    height: 420px;
    background-color: white;
    padding: 10px;
}
.booksContainerPublic
{
    width:100%;
    height: auto;
    padding: 10px;
}
.booksInCards
{
    width: 100%;
    height: auto;
    max-height: 450px;
    overflow-y: auto;
    display: flex;
    padding: 10px;
    flex-wrap: wrap;
}
.book
{
    border: 1px solid #d9d9d9;
    border-radius: 8px;
    width:120px;
    height: 200px;
    margin: 5px;
    transition: all 0.05ms ease;
    display: flex;
    justify-content: center;
}
.book:hover
{
    cursor:pointer;
    -webkit-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.75);
    box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.75);
}
.bookCover
{
    width: 100%;
    height: 100%;
    border-radius: 5px;
}
.description
{
    font-size: small;
    font-family: sans-serif;
}

</style>