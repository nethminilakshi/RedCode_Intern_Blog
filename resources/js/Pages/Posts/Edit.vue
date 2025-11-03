<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref } from "vue";

const props = defineProps({
    post: Object,
    categories: Array,
    allTags: Array,
});

const form = useForm({
    title: props.post.title,
    content: props.post.content,
    category_id: props.post.category_id,
    tags: props.post.tags.map((tag) => tag.name),
});

const tagInput = ref("");

const addTag = () => {
    const tag = tagInput.value.trim();
    if (tag && !form.tags.includes(tag)) {
        form.tags.push(tag);
        tagInput.value = "";
    }
};

const removeTag = (index) => {
    form.tags.splice(index, 1);
};

const submit = () => {
    form.put(route("posts.update", props.post.id), {
        onSuccess: () => {
            // Keep form data after success
        },
    });
};
</script>

<template>
    <Head title="Edit Post" />

    <div
        class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50"
    >
        <div class="container mx-auto py-12 max-w-3xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2
                            class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-700 to-pink-500"
                        >
                            Edit Post
                        </h2>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Title -->
                        <div>
                            <InputLabel for="title" value="Post Title *" />
                            <TextInput
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Enter post title..."
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.title"
                            />
                        </div>

                        <!-- Category -->
                        <div>
                            <InputLabel for="category_id" value="Category *" />
                            <select
                                id="category_id"
                                v-model="form.category_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option value="" disabled>
                                    Select a category
                                </option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="form.errors.category_id"
                            />
                        </div>

                        <!-- Tags -->
                        <div>
                            <InputLabel for="tags" value="Tags" />
                            <div class="mt-1 flex gap-2">
                                <input
                                    v-model="tagInput"
                                    @keydown.enter.prevent="addTag"
                                    type="text"
                                    class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    placeholder="Type a tag and press Enter"
                                />
                                <button
                                    @click.prevent="addTag"
                                    type="button"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                                >
                                    Add
                                </button>
                            </div>

                            <!-- Display tags -->
                            <div
                                v-if="form.tags.length > 0"
                                class="mt-3 flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="(tag, index) in form.tags"
                                    :key="index"
                                    class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm"
                                >
                                    {{ tag }}
                                    <button
                                        @click="removeTag(index)"
                                        type="button"
                                        class="hover:text-indigo-900"
                                    >
                                        ×
                                    </button>
                                </span>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.tags"
                            />
                        </div>

                        <!-- Content -->
                        <div>
                            <InputLabel for="content" value="Content *" />
                            <textarea
                                id="content"
                                v-model="form.content"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="10"
                                placeholder="Write your post content..."
                                required
                            ></textarea>
                            <InputError
                                class="mt-2"
                                :message="form.errors.content"
                            />
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">
                                <span v-if="form.processing">Updating...</span>
                                <span v-else>Update Post</span>
                            </PrimaryButton>

                            <Link
                                :href="route('posts.index')"
                                class="text-gray-600 hover:text-gray-900 font-medium"
                            >
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
