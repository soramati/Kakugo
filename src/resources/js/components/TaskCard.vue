<template>
<h1> タスクカード
</h1>

</template>

<script>

export default {
    props: {
        goal: Object
    },
    methods: {
        deleteGoal(id) {
            if (confirm('本当に削除しますか？')) {
                document.getElementById('form_' + id).submit();
            }

        }


    }
}
</script>

<style scoped>

</style>