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
    <title>列表</title>
</head>
<body>
<div id="app">
    <el-container>
        <el-main>
            <el-form :label-position="labelPosition" ref="installForm" :model="formLabelAlign" :rules="rules">
                <el-form-item label="安装日期" prop="install_date">
                    <el-date-picker type="date" placeholder="选择日期" value-format="yyyy-MM-dd" v-model="formLabelAlign.install_date"></el-date-picker>
                </el-form-item>
                <el-form-item label="宽带账号" prop="account">
                    <el-input v-model="formLabelAlign.account"></el-input>
                </el-form-item>
                <el-form-item label="用户地址" prop="address">
                    <el-input v-model="formLabelAlign.address"></el-input>
                </el-form-item>
                <el-form-item label="光猫设备">
                    <template v-for="(equipment,index) in formLabelAlign.equipments" v-bind="index">
                        <template v-if="equipment.type == 3">
                            <el-input v-model="equipment.content">
                                <template slot="append">
                                    <el-button type="primary" @click="removeEquipment(index)" icon="el-icon-delete"></el-button>
                                </template>
                            </el-input>
                        </template>
                    </template>
                    <el-button type="primary" @click="addEquipment(3)" icon="el-icon-plus"></el-button>
                </el-form-item>
                <el-form-item label="机顶盒安装情况">
                    <template v-for="(equipment,index) in formLabelAlign.equipments" v-bind="index">
                        <template v-if="equipment.type == 1">
                            <el-input v-model="equipment.content">
                                <template slot="append">
                                    <el-button type="primary" @click="removeEquipment(index)" icon="el-icon-delete"></el-button>
                                </template>
                            </el-input>
                        </template>
                    </template>
                    <el-button type="primary" @click="addEquipment(1)" icon="el-icon-plus"></el-button>
                </el-form-item>
                <el-form-item label="路由组网设备及安防设备">
                    <template v-for="(equipment,index) in formLabelAlign.equipments" v-bind="index">
                        <template v-if="equipment.type == 2">
                            <el-input v-model="equipment.content">
                                <template slot="append">
                                    <el-button type="primary" @click="removeEquipment(index)" icon="el-icon-delete"></el-button>
                                </template>
                            </el-input>
                        </template>
                    </template>
                    <el-button type="primary" @click="addEquipment(2)" icon="el-icon-plus"></el-button>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input v-model="formLabelAlign.remark"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button @click="addInstall">新增</el-button>
                </el-form-item>
            </el-form>

        </el-main>
    </el-container>
</div>

<script>
    axios.interceptors.request.use(
        config => {
            const token = localStorage.getItem('token')
            token && (config.headers.Authorization = 'Bearer ' + token)
            return config
        }
    )
    axios.interceptors.response.use(
        response => {
            if (response.data.code == 429) {
                window.location = '/login'
            }
            const token = response.headers.token ? response.headers.token : ''
            token && localStorage.setItem('token', token)
            return response
        }
    )
    if (!localStorage.getItem('token')) {
        window.location = '/login'
    }
    var app = new Vue({
        el: '#app',
        data: {
            labelPosition: 'top',
            id: 0,
            formLabelAlign: {
                mobile: '',
                password: '',
                install_date: '',
                address: '',
                equipments: [],
                remark: '',
            },
            rules: {
                install_date: [
                    {required: true, message: "安装日期不能为空", trigger: 'blur'},
                ],
                account: [
                    {required: true, message: "宽带账号不能为空", trigger: 'blur'},
                ],
                address: [
                    {required: true, message: "用户地址不能为空", trigger: 'blur'},
                ],
            }
        },
        methods: {
            addEquipment(type) {
                this.formLabelAlign.equipments.push({
                    type: type,
                    content: ''
                })
            },
            removeEquipment(index) {
                this.formLabelAlign.equipments.splice(index, 1)
            },
            addInstall() {
                this.$refs['installForm'].validate((valid) => {
                    if (valid) {
                        if (this.id) {
                            axios.put('/api/install/' + this.id, this.formLabelAlign)
                                .then((response) => {
                                    if (!response.data.success) {
                                        this.$message({
                                            message: response.data.err_msg,
                                            type: 'error'
                                        });
                                    } else {
                                        this.$message({
                                            message: '新增成功',
                                            type: 'success'
                                        });
                                        setTimeout(function () {
                                            window.location = '/install_list'
                                        }, 200);
                                    }
                                });
                        } else {
                            axios.post('/api/install', this.formLabelAlign)
                                .then((response) => {
                                    if (!response.data.success) {
                                        this.$message({
                                            message: response.data.err_msg,
                                            type: 'error'
                                        });
                                    } else {
                                        this.$message({
                                            message: '新增成功',
                                            type: 'success'
                                        });
                                        setTimeout(function () {
                                            window.location = '/install_list'
                                        }, 200);
                                    }
                                });
                        }

                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });

            },
            getQueryVariable(variable) {
                var query = window.location.search.substring(1);
                var vars = query.split("&");
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    if (pair[0] == variable) {
                        return pair[1];
                    }
                }
                return false;
            },
            getData() {
                var id = this.getQueryVariable('id');
                if (!id) {
                    this.$message({
                        message: '数据异常',
                        type: 'error'
                    });
                }
                this.id = id
                axios.get('/api/install/' + id)
                    .then((response) => {
                        if (!response.data.success) {
                            this.$message({
                                message: response.data.err_msg,
                                type: 'error'
                            });
                        } else {
                            this.formLabelAlign = response.data.data
                        }
                    });
            }
        },
        mounted() {
            this.id = this.getQueryVariable('id') ? this.getQueryVariable('id') : 0;
            if (this.id) {
                this.getData()
            }
        }
    })
</script>
</body>
</html>
