<div class="flex justify-center fixed top-0 right-0 left-0 z-50 border-b border-[#DBDFE9] bg-white bg-opacity-50 backdrop-blur-md">
    <div class="w-full max-w-[1500px]">
        <div class="flex items-center justify-between p-5">
            <h1 class="text-lg font-bold">Belanja<span class="text-blue-500">Disini.</span></h1>

            <div class="relative">
                <div id="profileButton" class="bg-blue-500 hover:bg-blue-600 duration-300 p-1 w-10 h-10 rounded-full flex items-center justify-center cursor-pointer">
                    <p class="text-white text-md font-bold">A</p>
                </div>

                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <ul class="py-2 text-gray-700">
                        <li>
                            <a href="/admin/account" class="block px-4 py-2 hover:bg-gray-100">Account</a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const profileButton = document.getElementById("profileButton");
    const dropdownMenu = document.getElementById("dropdownMenu");

    profileButton.addEventListener("click", () => {
        dropdownMenu.classList.toggle("hidden");
        profileButton.classList.toggle("ring");
        profileButton.classList.toggle("ring-blue-600");
        profileButton.classList.toggle("ring-offset-4");
    });

    document.addEventListener("click", (event) => {
        if (!profileButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add("hidden");
            profileButton.classList.remove("ring", "ring-blue-600", "ring-offset-4");
        }
    });
</script>
