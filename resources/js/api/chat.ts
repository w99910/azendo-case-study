export default async (message: string, history: string[], onChunk: (chunk: string) => void) => {
    const _token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const response = await fetch('/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message, history, _token }),
    });

    if (!response.body) {
        throw new Error('ReadableStream not supported in this browser.');
    }

    const reader = response.body.getReader();
    const decoder = new TextDecoder();

    while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        onChunk(decoder.decode(value));
    }
};