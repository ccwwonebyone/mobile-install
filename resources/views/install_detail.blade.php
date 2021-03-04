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
            <el-form position="top" ref="loginForm" :model="formLabelAlign">
                <el-form-item label="安装日期">
                    <el-input v-model="formLabelAlign.install_date" disabled></el-input>
                </el-form-item>
                <el-form-item label="宽带账号">
                    <el-input v-model="formLabelAlign.account" disabled></el-input>
                </el-form-item>
                <el-form-item label="用户地址">
                    <el-input v-model="formLabelAlign.address" disabled></el-input>
                </el-form-item>
                <el-form-item label="光猫设备">
                    <template v-for="(equipment,index) in formLabelAlign.equipments" v-bind="index">
                        <template v-if="equipment.type == 3">
                            <el-input v-model="equipment.content" disabled>
                            </el-input>
                        </template>
                    </template>
                </el-form-item>
                <el-form-item label="机顶盒安装情况">
                    <template v-for="(equipment,index) in formLabelAlign.equipments" v-bind="index">
                        <template v-if="equipment.type == 1">
                            <el-input v-model="equipment.content" disabled>
                            </el-input>
                        </template>
                    </template>
                </el-form-item>
                <el-form-item label="路由组网设备及安防设备">
                    <template v-for="(equipment,index) in formLabelAlign.equipments" v-bind="index">
                        <template v-if="equipment.type == 2">
                            <el-input v-model="equipment.content" disabled>

                            </el-input>
                        </template>
                    </template>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input v-model="formLabelAlign.remark" disabled></el-input>
                </el-form-item>
            </el-form>

        </el-main>
    </el-container>
</div>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            formLabelAlign: {
                id: 0,
                install_date: '2021-02-11',
                equipments: [],
                remark: '',
                account: '',
            }

        },
        methods: {
            handleCurrentChange(currentPage) {
                this.currentPage = currentPage
                this.getData()
            },
            handleDelete(index, row) {
                axios.delete('/api/install/' + row.id)
                    .then((response) => {
                        if (!response.data.success) {
                            this.$message({
                                message: response.data.err_msg,
                                type: 'error'
                            });
                        } else {
                            this.getData()
                        }
                    });
            },
            handleEdit(index, row) {

            },
            getData() {
                var id = this.getQueryVariable('id');
                if (!id) {
                    this.$message({
                        message: '数据异常',
                        type: 'error'
                    });
                }
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
            }
        },
        mounted() {
            if (!localStorage.getItem('token')) {
                window.location = '/login'
            }
            this.getData();
        }
    })
</script>
</body>
</html>
