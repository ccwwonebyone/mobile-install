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
    {{--    <script src="https://www.unpkg.com/vconsole@3.4.0/dist/vconsole.min.js"></script>--}}
    {{--    <script>--}}
    {{--        var vConsole = new VConsole();--}}
    {{--    </script>--}}
    <title>列表</title>
</head>
<body>
<div id="app">
    <el-container>

        <div style="width: 100%">
            <div>
                <el-form :inline="true" :model="searchForm" class="demo-form-inline">
                    <el-form-item label="安装日期">
                        <el-date-picker
                            v-model="searchForm.install_date"
                            type="date"
                            value-format="yyyy-MM-dd"
                            placeholder="选择日期">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="宽带账号">
                        <el-input v-model="searchForm.account" placeholder="宽带账号"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="getData">查询</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div>
                <el-button type="primary" @click="addEquipment()" icon="el-icon-plus"></el-button>
            </div>
            <el-table
                :data="tableData"
                style="width: 100%">
                <el-table-column
                    prop="install_date"
                    label="安装日期">
                </el-table-column>
                <el-table-column
                    prop="account"
                    label="宽带账号">
                </el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button type="primary" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)" circle></el-button>
                        <el-button type="danger" @click="handleDelete(scope.$index, scope.row)" icon="el-icon-delete" circle></el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination
                @current-change="handleCurrentChange"
                :current-page="currentPage"
                :page-size="size"
                layout="total, prev, pager, next"
                :total="total">
            </el-pagination>
        </div>
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
            total: 0,
            size: 10,
            tableData: [{
                id: 0,
                install_date: '',
                account: ''
            }],
            searchForm: {
                account: '',
                install_date: '',
            },
            currentPage: 1,

        },
        methods: {
            handleSizeChange(size) {

            },
            handleCurrentChange(currentPage) {
                this.currentPage = currentPage
                this.getData()
            },
            handleDelete(index, row) {
                axios.delete('api/install/' + row.id).then((response) => {
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
            addEquipment() {
                window.location = '/install'
            },
            handleEdit(index, row) {
                window.location = '/install?id=' + row.id
            },
            getData() {
                const install_date = this.searchForm.install_date ? [this.searchForm.install_date, this.searchForm.install_date] : ''
                axios.get('/api/install', {
                    params: {
                        page: this.currentPage,
                        num: this.size,
                        address: this.searchForm.address,
                        account: this.searchForm.account,
                        install_date: install_date
                    }
                })
                    .then((response) => {
                        if (!response.data.success) {
                            this.$message({
                                message: response.data.err_msg,
                                type: 'error'
                            });
                        } else {
                            this.tableData = response.data.data.data
                            this.total = response.data.data.total
                        }
                    });
            }
        },
        mounted() {
            this.getData();
        }
    })
</script>
</body>
</html>
