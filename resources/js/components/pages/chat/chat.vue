<template>
    <div class="container mx-auto h-full py-4 md:py-6 lg:py-8">
        <div :class="[
            'w-full flex relative h-full gap-x-4 ',
            messages.length > 0 ? '' : 'justify-center'
        ]">
            <!-- Left Column: Chat Interface -->
            <transition name="chat-move">
                <div :class="[
                    'flex flex-col h-full absolute backdrop-blur-2xl bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 rounded-lg shadow-md overflow-hidden border border-zinc-200/50 dark:border-zinc-700/50',
                    messages.length > 0 ? 'w-8/12 left-0 translate-x-0' : 'w-full transform translate-x-1/2 -left-1/2'
                ]">
                    <!-- Chat Header (Optional) -->
                    <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex-shrink-0">
                        <h1 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">Chat Assistant</h1>
                    </div>

                    <!-- Message Display Area -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4" ref="messageContainer">
                        <div v-if="messages.length === 0 && !isLoading"
                            class="text-center text-zinc-400 dark:text-zinc-500 mt-10">
                            <span>No messages yet. Start the conversation!</span>
                        </div>
                        <div v-for="message in messages" :key="message.id"
                            :class="['flex', message.role === 'user' ? 'justify-end' : 'justify-start']">
                            <div :class="[
                                'max-w-xs lg:max-w-md xl:max-w-lg px-4 py-2 rounded-lg shadow',
                                message.role === 'user'
                                    ? 'bg-blue-500 text-white border border-blue-400 dark:border-blue-600'
                                    : 'bg-zinc-50/30 border border-zinc-100 dark:border-zinc-600 dark:bg-zinc-700 text-zinc-900 dark:text-zinc-50',
                                message.products ? 'mb-2' : '' // Add margin if products are attached
                            ]">
                                <p class="text-sm" v-html="message.content.trim()"></p>
                                <!-- Chain of Thoughts Toggle -->
                                <div v-if="message.role === 'assistant' && message.thoughts && message.thoughts.length"
                                    class="mt-3">
                                    <button @click="toggleThoughts(message.id)"
                                        class="text-xs cursor-pointer text-blue-600 dark:text-blue-400 underline mb-2 focus:outline-none">
                                        {{ expandedThoughts[message.id] ? 'Hide' : 'Show' }} Thoughts
                                    </button>
                                    <div v-show="expandedThoughts[message.id]"
                                        class="border-l-2 border-blue-400 pl-4 mt-2">
                                        <div v-for="(thought, idx) in message.thoughts" :key="idx"
                                            class="mb-2 flex items-start">
                                            <span
                                                class="w-2 h-2 mt-1 mr-2 bg-blue-400 rounded-full flex-shrink-0"></span>
                                            <span class="text-xs text-zinc-700 dark:text-zinc-200">{{ thought }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Loading animation for AI response -->
                        <div v-if="isLoading" class="flex items-start justify-start mt-2">
                            <div class="flex items-center gap-2 animate-pulse">
                                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                            </div>
                            <span class="ml-2 text-zinc-400 dark:text-zinc-500 text-xs"> {{ aiResponse.message }}</span>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div
                        class="p-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 flex-shrink-0">
                        <div class="flex items-center gap-2">
                            <input type="text" v-model="newMessage" @keyup.enter="sendMessage"
                                placeholder="Ask about products or anything else..."
                                class="flex-1 px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-zinc-100 text-sm" />
                            <button @click="sendMessage" :disabled="!newMessage.trim() || isLoading"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium p-2 rounded-md shadow transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 16.571V11.5a1 1 0 012 0v5.071a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Right Column: Suggested Products -->
            <transition name="fade-slide">
                <div v-if="messages.length > 0"
                    class="w-[32%] absolute right-0 backdrop-blur-2xl bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 justify-self-end rounded-lg shadow-md overflow-hidden flex flex-col h-full border border-zinc-200/50 dark:border-zinc-700/50">
                    <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex-shrink-0">
                        <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">Suggested Products</h2>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-3">
                        <!-- Placeholder for Products -->
                        <div v-show="isLoading" v-for="i in [0, 1, 2,]" :key="i"
                            class="bg-zinc-50 animate-pulse dark:bg-zinc-800 rounded-lg shadow-sm p-3 border border-zinc-200 dark:border-zinc-700 flex gap-3 items-center">
                            <div class="w-16 h-16 animate-pulse rounded-md bg-zinc-200 dark:bg-zinc-700"></div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-zinc-900 dark:text-zinc-50 mb-0.5"></div>
                            </div>
                        </div>

                        <div v-show="!isLoading" v-for="product in suggestedProducts" :key="product.id"
                            class="bg-zinc-50 dark:bg-zinc-800 rounded-lg shadow-sm p-3 border border-zinc-200 dark:border-zinc-700 flex gap-3 items-center">
                            <img :src="product.image" alt="Product Image" class="w-16 h-16 rounded-md" />
                            <div class="flex-1">
                                <div class="font-medium text-sm text-zinc-900 dark:text-zinc-50 mb-0.5">{{ product.name
                                }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">{{ product.category }} - {{
                                    product.brand }}</div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">${{
                                        product.price }}</span>
                                    <button
                                        class="text-xs bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-200 px-2 py-1 rounded">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick, onMounted, computed, reactive } from 'vue';
import chat from '@/api/chat';
import { marked } from 'marked';

// Start with no messages
const messages = ref([]);
const newMessage = ref('Tell me about Maybelline Face Studio Master Hi-Light Light Booster Bronzer');
const messageContainer = ref(null);
const isLoading = ref(false);
const aiResponse = reactive({
    message: '',
    products: []
});

// Computed property to extract all suggested products from AI messages
const suggestedProducts = ref([]);

const scrollToBottom = async () => {
    await nextTick();
    if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
    }
};

const extractThoughts = (text) => {
    const thoughts = [];
    const regex = /<think>([\s\S]*?)<\/think>/g;
    let match;
    while ((match = regex.exec(text)) !== null) {
        thoughts.push(match[1].trim());
    }
    return thoughts;
};

const decodeUnicodeEscapes = (text) => {
    // Decode unicode escape sequences like \uXXXX
    let decoded = text.replace(/\\u([\dA-Fa-f]{4})/g, (match, grp) => {
        return String.fromCharCode(parseInt(grp, 16));
    });
    // Decode literal escape sequences like \n, \r, \t
    decoded = decoded.replace(/\\n/g, '\n').replace(/\\r/g, '\r').replace(/\\t/g, '\t');
    return decoded;
};

// Helper to remove <think> blocks entirely
const removeThinkBlocks = (text) => {
    // Regex to match literal <think>...</think> blocks
    // Assumes unicode escapes have already been decoded.
    const thinkBlockRegex = /<think>[\s\S]*?<\/think>\s*/g;
    return text.replace(thinkBlockRegex, '').trim();
};

// Track which messages have their thoughts expanded
const expandedThoughts = ref({});
const toggleThoughts = (id) => {
    expandedThoughts.value[id] = !expandedThoughts.value[id];
};

const sendMessage = async () => {
    const text = newMessage.value.trim();
    if (!text || isLoading.value) return;

    const userMessage = {
        id: messages.value.length + 1,
        role: 'user',
        content: text,
    };
    messages.value.push(userMessage);
    newMessage.value = '';
    scrollToBottom();
    isLoading.value = true;

    // --- Simulate AI response --- Replace with actual API call
    aiResponse.message = ``; // Reset buffer for new response

    chat(text, messages.value, (chunk) => {
        aiResponse.message += chunk; // Accumulate chunks

        // Check if the accumulated message contains the closing tag
        // Process only when the final part with products arrives
        if (chunk.includes('</suggestedProducts>')) {
            try {
                // Decode the entire message first
                let decodedMessage = decodeUnicodeEscapes(aiResponse.message);

                // Extract products first, ensuring the JSON is complete
                const productMatch = decodedMessage.match(/<suggestedProducts>([\s\S]*?)<\/suggestedProducts>/);
                if (productMatch && productMatch[1]) {
                    // Assign products to the reactive aiResponse or directly to the message object later
                    // For now, let's store them temporarily


                    // Clean the JSON string by removing control characters that cause parsing issues
                    const cleanedJson = productMatch[1]
                        .replace(/[\u0000-\u001F\u007F-\u009F]/g, '') // Remove all control characters
                        .replace(/\n/g, '') // Also remove newlines
                        .trim();

                    // Parse the cleaned JSON
                    const parsedProducts = JSON.parse(cleanedJson);
                    aiResponse.products = parsedProducts; // Store in aiResponse for now
                } else {
                    aiResponse.products = []; // Handle case where tag exists but content is missing/invalid
                }

                // strip the decodedMessage of the <suggestedProducts>...</suggestedProducts> tags
                decodedMessage = decodedMessage.replace(/<suggestedProducts>([\s\S]*?)<\/suggestedProducts>/, '');

            } catch (error) {
                console.error("Error parsing suggested products JSON or decoding:", error, chunk);
                aiResponse.products = []; // Reset products on error
                suggestedProducts.value = [];
            }

            // Decode again just in case some escapes were missed or part of the chunk logic split them
            // (This might be redundant depending on streaming logic, but safer)
            let finalDecodedMessage = decodeUnicodeEscapes(aiResponse.message);
            // Strip product tags *again* from the potentially re-decoded message
            finalDecodedMessage = finalDecodedMessage.replace(/<suggestedProducts>([\s\S]*?)<\/suggestedProducts>/, '');


            // Extract thoughts from the *decoded* and product-stripped message
            const thoughts = extractThoughts(finalDecodedMessage); // extractThoughts assumes decoded tags
            // Remove all <think> blocks for the final displayed message
            let cleanText = removeThinkBlocks(finalDecodedMessage);

            // Remove prefixes and leading whitespace/newlines
            cleanText = cleanText.replace(/Finding products\.\.\./g, '').replace(/Generating response\.\.\./g, '');
            cleanText = cleanText.replace(/^[\s\r\n]+/, '');

            // Convert newline characters to <br/> and trim
            cleanText = cleanText.replace(/\n+/g, '<br/>').trim();

            // Convert markdown to HTML
            const htmlText = marked(cleanText);

            messages.value.push({
                id: messages.value.length + 1,
                role: 'assistant',
                content: htmlText, // Use the converted HTML
                thoughts: thoughts,
            });

            suggestedProducts.value = aiResponse.products;

            // Reset state for the next interaction
            aiResponse.message = '';
            isLoading.value = false;
            scrollToBottom(); // Scroll after adding the final message

        } else if (!isLoading.value && chunk.trim()) {
            // Decode the final accumulated message
            let finalDecodedMessage = decodeUnicodeEscapes(aiResponse.message);
            // Handle potential final text chunk after products, if streaming ends without product tag
            const thoughts = extractThoughts(finalDecodedMessage);
            let cleanText = removeThinkBlocks(finalDecodedMessage);

            // Remove prefixes and leading whitespace/newlines
            cleanText = cleanText.replace(/Finding products\.\.\./g, '').replace(/Generating response\.\.\./g, '');
            cleanText = cleanText.replace(/^[\s\r\n]+/, '');

            // Trim and convert to HTML
            const htmlText = marked(cleanText.trim());

            suggestedProducts.value = aiResponse.products;

            messages.value.push({
                id: messages.value.length + 1,
                role: 'assistant',
                content: htmlText, // Use the converted HTML
                thoughts: thoughts
            });
            aiResponse.message = '';
            isLoading.value = false;
            scrollToBottom();
        }
        // If still loading and no product tag yet, just continue accumulating
        else if (isLoading.value) {
            // Optionally update a temporary loading message if needed
            // e.g., aiResponse.loadingText = removeThinkBlocks(aiResponse.message);
        }

    }).catch(error => {
        console.error("Chat API error:", error);
        // Handle error display
        messages.value.push({
            id: messages.value.length + 1,
            role: 'assistant',
            content: 'Sorry, I encountered an error. Please try again.',
            thoughts: []
        });
        isLoading.value = false;
        aiResponse.message = '';
        suggestedProducts.value = [];
        scrollToBottom();
    });
};

onMounted(() => {
    scrollToBottom();
    // Optionally send initial message on mount if needed
    // sendMessage();
});

</script>

<style scoped>
/* Ensure the parent container allows this component to take full height */
/* Add custom scrollbar styles if desired (like in the search component) */
.clear-left {
    clear: left;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: opacity 0.8s, transform 0.8s;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}

/* Chat section move animation */
.chat-move-enter-active,
.chat-move-leave-active {
    transform-origin: center;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.chat-move-enter-from,
.chat-move-leave-to {
    transform: translateX(0);
}

.chat-move-enter-to,
.chat-move-leave-from {
    transform: translateX(0);
}

/* Chat width transition */
.chat-width-center {
    transform-origin: right right;
    /* w-8/12 */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chat-width-full {
    transform-origin: right right;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>