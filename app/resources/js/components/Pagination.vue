<template>
  <div>
    <div class="page" v-for="pageNumber in visiblePages" :key="pageNumber">
      <p>Página {{ pageNumber }} de {{ totalPages }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    totalPages: {
      type: Number,
      required: true
    },
    startPage: {
      type: Number,
      default: 1
    },
    endPage: {
      type: Number
    }
  },
  computed: {
    visiblePages() {
      const pages = [];
      for (let i = this.startPage; i <= (this.endPage || this.totalPages); i++) {
        pages.push(i);
      }
      return pages;
    }
  }
}
</script>