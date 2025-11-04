<script setup>
import { Head, Link, useForm, usePage, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

const props = defineProps({
    posts: Object,
    categories: Array,
    filters: Object,
});

const page = usePage();
const successMessage = computed(() => page.props.flash?.success);

// Category filter state
const selectedCategory = ref(props.filters?.category || "");

// Watch for category changes and filter
watch(selectedCategory, (newValue) => {
    router.get(
        route("posts.index"),
        {
            category: newValue,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
});

const deleteForm = useForm({});

const deletePost = (postId) => {
    if (confirm("Are you sure you want to delete this post?")) {
        deleteForm.delete(route("posts.delete", postId));
    }
};
</script>

<template>
    <Head title="Blog Posts" />
    <div
        class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50"
    >
        <div class="container mx-auto py-12 max-w-7xl sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div
                v-if="successMessage"
                class="mb-6 bg-green-200 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert"
            >
                <span class="block sm:inline">{{ successMessage }}</span>
            </div>

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3
                    class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-700 to-pink-500"
                >
                    Blog Posts
                </h3>
                <Link
                    :href="route('posts.create')"
                    class="px-4 py-2 bg-pink-700 text-white rounded-lg hover:bg-purple-900 transition"
                >
                    Create New Post
                </Link>
            </div>

            <!-- Category Filter -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-2">
                <div class="flex items-center gap-4">
                    <label
                        for="category-filter"
                        class="font-semibold text-gray-700 text-sm"
                    >
                        Filter by Category:
                    </label>
                    <select
                        id="category-filter"
                        v-model="selectedCategory"
                        class="text-sm px-2 py-1 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent"
                    >
                        <option value="">All Categories</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Posts Grid -->
            <div
                v-if="posts.data.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6"
            >
                <div
                    v-for="post in posts.data"
                    :key="post.id"
                    class="group bg-white shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-800 rounded-lg overflow-hidden"
                >
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3
                                class="text-2xl font-bold text-purple-500 hover:text-purple-800 transition"
                            >
                                {{ post.title }}
                            </h3>
                            <span
                                v-if="post.category"
                                class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full"
                            >
                                {{ post.category.name }}
                            </span>
                        </div>

                        <!-- Tags Display -->
                        <div
                            v-if="post.tags && post.tags.length > 0"
                            class="mb-3 flex flex-wrap gap-2"
                        >
                            <span
                                v-for="tag in post.tags"
                                :key="tag.id"
                                class="px-2 py-1 bg-pink-100 text-pink-700 text-xs rounded-full"
                            >
                                #{{ tag.name }}
                            </span>
                        </div>

                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ post.content }}
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-4">
                            <Link
                                :href="route('posts.edit', post.id)"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-400 transition text-sm"
                            >
                                Edit
                            </Link>
                            <button
                                @click="deletePost(post.id)"
                                class="px-4 py-2 bg-red-800 text-white rounded-lg hover:bg-red-500 transition text-sm"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-lg shadow-sm p-12 text-center">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    No posts yet
                </h3>
                <p class="text-gray-600 mb-6">
                    Start creating your first blog post!
                </p>
                <Link
                    :href="route('posts.create')"
                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    Create Your First Post
                </Link>
            </div>

            <!--  Pagination Section -->
            <div v-if="posts.prev_page_url || posts.next_page_url" class="mt-8">
                <div class="flex justify-center items-center gap-4">
                    <!-- Previous Button -->
                    <Link
                        v-if="posts.prev_page_url"
                        :href="posts.prev_page_url"
                        preserve-scroll
                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 transition font-medium"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Previous
                    </Link>
                    <span
                        v-else
                        class="inline-flex items-center px-6 py-3 border border-gray-200 rounded-lg text-gray-400 cursor-not-allowed font-medium"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Previous
                    </span>

                    <!-- Next Button -->
                    <Link
                        v-if="posts.next_page_url"
                        :href="posts.next_page_url"
                        preserve-scroll
                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 transition font-medium"
                    >
                        Next
                        <svg
                            class="w-5 h-5 ml-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </Link>
                    <span
                        v-else
                        class="inline-flex items-center px-6 py-3 border border-gray-200 rounded-lg text-gray-400 cursor-not-allowed font-medium"
                    >
                        Next
                        <svg
                            class="w-5 h-5 ml-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
