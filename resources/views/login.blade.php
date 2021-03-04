<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 引入样式 -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <script src="https://unpkg.com/vue@2.6.12/dist/vue.js"></script>
    <!-- 引入组件库 -->
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>登录</title>
</head>
<body>
<div id="app">
    <el-container>
        <el-header style="line-height: 50px;text-align: center">登录</el-header>
        <el-main>
            <el-form :label-position="labelPosition" :rules="rules" ref="loginForm" label-width="60px" :model="formLabelAlign">
                <el-form-item label="账号" prop="mobile">
                    <el-input v-model="formLabelAlign.mobile"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input v-model="formLabelAlign.password"></el-input>
                </el-form-item>
                <el-form-item style="text-align: center">
                    <el-button type="primary" @click="login">登录</el-button>
                    <el-button>注册</el-button>
                </el-form-item>
            </el-form>

        </el-main>
    </el-container>
</div>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            labelPosition: 'right',
            formLabelAlign: {
                mobile: '',
                password: ''
            },
            rules: {
                mobile: [
                    {required: true, message: "账号不可为空", trigger: 'blur'},
                ],
                password: [
                    {required: true, message: "密码不可为空", trigger: 'blur'},
                ]
            }
        },
        methods: {
            login() {
                this.$refs['loginForm'].validate((valid) => {
                    if (valid) {
                        axios.post('/api/login', this.formLabelAlign)
                            .then((response) => {
                                if (!response.data.success) {
                                    this.$message({
                                        message: response.data.err_msg,
                                        type: 'error'
                                    });
                                } else {
                                    localStorage.setItem('token', response.data.data.token)
                                    window.location = '/install_list'
                                }
                            });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            }
        }
    })
</script>
</body>
</html>
