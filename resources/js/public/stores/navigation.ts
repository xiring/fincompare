/**
 * Navigation Store
 * Centralized state management for navigation UI
 */

import { defineStore } from 'pinia';
import { ref, type Ref } from 'vue';

export const useNavigationStore = defineStore('navigation', () => {
  const mobileMenuOpen = ref<boolean>(false);
  const megaMenu = ref<'products' | null>(null);
  const expertPopoverOpen = ref<boolean>(false);

  /**
   * Toggle mobile menu
   */
  const toggleMobileMenu = (): void => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
  };

  /**
   * Close mobile menu
   */
  const closeMobileMenu = (): void => {
    mobileMenuOpen.value = false;
  };

  /**
   * Open mobile menu
   */
  const openMobileMenu = (): void => {
    mobileMenuOpen.value = true;
  };

  /**
   * Set mega menu state
   */
  const setMegaMenu = (menu: 'products' | null): void => {
    megaMenu.value = menu;
  };

  /**
   * Close mega menu
   */
  const closeMegaMenu = (): void => {
    megaMenu.value = null;
  };

  /**
   * Toggle expert popover
   */
  const toggleExpertPopover = (): void => {
    expertPopoverOpen.value = !expertPopoverOpen.value;
  };

  /**
   * Open expert popover
   */
  const openExpertPopover = (): void => {
    expertPopoverOpen.value = true;
  };

  /**
   * Close expert popover
   */
  const closeExpertPopover = (): void => {
    expertPopoverOpen.value = false;
  };

  /**
   * Close all navigation menus
   */
  const closeAll = (): void => {
    mobileMenuOpen.value = false;
    megaMenu.value = null;
    expertPopoverOpen.value = false;
  };

  return {
    // State
    mobileMenuOpen,
    megaMenu,
    expertPopoverOpen,
    // Actions
    toggleMobileMenu,
    closeMobileMenu,
    openMobileMenu,
    setMegaMenu,
    closeMegaMenu,
    toggleExpertPopover,
    openExpertPopover,
    closeExpertPopover,
    closeAll,
  };
});

