<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Inicio de sesión</span>
            </div>
            <el-input placeholder="Usuario" class="inputForm" v-model="credentials.user"></el-input>
            <br><br>
            <el-input placeholder="Contraseña" class="inputForm" v-model="credentials.pwd" show-password></el-input>
            <el-button class="btnLogin" type="warning" @click="sendParams()">Iniciar sesión</el-button>
        </el-card>
    </div>
</template>
<script>
export default {
    props:[],
    data() {
        return {
             credentials:{
                user: null,
                pwd: null
            }
        }
    },
    mounted()
    {

    },
    methods: {
        sendParams()
        {
            axios.post('login', {
                'credentials': this.credentials
            }).then(response => {
                if(response.data.code == "200")
                    location.href="/libreria/public/admin";
                else
                    this.$notify.error({
                        title: 'Error',
                        message: response.data.message
                    });
                
            });
        }
    },
}
</script>
<style>
.inputForm
{
    text-align: center !important;
}
.btnLogin
{
   margin: 0 auto;
   margin-top:20px;
}
</style>