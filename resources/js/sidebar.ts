const initializeSidebar = (): void => {
    const sidebar = document.querySelector<HTMLElement>("#mobile-sidebar");
    const toggleButton = document.querySelector<HTMLElement>(
        "[data-sidebar-toggle]",
    );
    const wash = document.querySelector<HTMLElement>("[data-sidebar-wash]");
    const closeButtons = document.querySelectorAll<HTMLElement>(
        "[data-sidebar-close]",
    );

    if (
        !sidebar ||
        !toggleButton ||
        !wash ||
        sidebar.dataset.mobileSidebarInitialized === "true"
    ) {
        return;
    }

    sidebar.dataset.mobileSidebarInitialized = "true";

    let isOpen = false;

    const setSidebarState = (nextState: boolean): void => {
        isOpen = nextState;

        sidebar.classList.toggle("translate-x-0", isOpen);
        sidebar.classList.toggle("-translate-x-full", !isOpen);
        sidebar.classList.toggle("pointer-events-auto", isOpen);
        sidebar.classList.toggle("pointer-events-none", !isOpen);
        wash.classList.toggle("pointer-events-auto", isOpen);
        wash.classList.toggle("pointer-events-none", !isOpen);
        wash.classList.toggle("opacity-100", isOpen);
        wash.classList.toggle("opacity-0", !isOpen);
        wash.setAttribute("aria-hidden", isOpen ? "false" : "true");
        toggleButton.setAttribute("aria-expanded", isOpen ? "true" : "false");
        document.body.classList.toggle("overflow-hidden", isOpen);
    };

    toggleButton.addEventListener("click", () => {
        setSidebarState(!isOpen);
    });

    closeButtons.forEach((closeButton) => {
        closeButton.addEventListener("click", () => {
            setSidebarState(false);
        });
    });

    document.addEventListener("keydown", (event: KeyboardEvent) => {
        if (event.key === "Escape") {
            setSidebarState(false);
        }
    });

    setSidebarState(false);
};

let sidebarListenersRegistered = false;

const registerSidebarListeners = (): void => {
    if (sidebarListenersRegistered) {
        return;
    }

    document.addEventListener(
        "DOMContentLoaded",
        () => {
            initializeSidebar();
        },
        { once: true },
    );

    document.addEventListener("livewire:navigated", () => {
        initializeSidebar();
    });

    sidebarListenersRegistered = true;
};

registerSidebarListeners();
initializeSidebar();
