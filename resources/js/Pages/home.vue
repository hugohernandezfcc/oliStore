<template>
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-red-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">Tienda de Abarrotes</h1>
                <nav>
                    <a href="#productos" class="mx-2 hover:underline">Productos</a>
                    <a href="#contacto" class="mx-2 hover:underline">Contacto</a>
                </nav>
            </div>
        </header>

        <!-- Body -->
        <main class="flex-grow container mx-auto p-6">
            <section class="text-center mb-10">
                <h2 class="text-3xl font-semibold mb-4">Bienvenido a tu tienda de confianza</h2>
                <p class="text-gray-700">Ofrecemos productos frescos y de calidad para tu hogar.</p>
            </section>

            <section id="productos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="border p-4 rounded shadow" v-for="i in 6" :key="i">
                    <h3 class="text-xl font-bold mb-2">Producto {{ i }}</h3>
                    <p class="text-gray-600">Descripción breve del producto.</p>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white text-center p-4">
            <p>&copy; 2025 Tienda de Abarrotes. Todos los derechos reservados.</p>
        </footer>
    </div>
</template>

<script setup>
// No necesitas lógica de momento, pero puedes agregar composables o stores si los usas.
</script>
<script>
export default{
    name: 'Home',
    data(){
        return {
            info:''
        }
    },
    mounted(){
        console.log('mounted',this.info);
    },
    computed: {

    }
}
</script>
