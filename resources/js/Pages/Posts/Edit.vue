<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    post: Object,
});

const form = useForm({
    title: props.post.title,
    content: props.post.content,
});

const submit = () => {
    form.put(route("posts.update", props.post.id), {
        onSuccess: () => {
            form.reset();
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
                                class="text-gray-600 hover:text-gray-900"
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
