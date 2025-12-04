/**
 * Navigation Store
 * Centralized state management for navigation UI
 */

import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useNavigationStore = defineStore('navigation', () => {
  const mobileMenuOpen = ref(false);
  const megaMenu = ref(null); // 'products' | null
  const expertPopoverOpen = ref(false);

  /**
   * Toggle mobile menu
   */
  const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
  };

  /**
   * Close mobile menu
   */
  const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
  };

  /**
   * Open mobile menu
   */
  const openMobileMenu = () => {
    mobileMenuOpen.value = true;
  };

  /**
   * Set mega menu state
   */
  const setMegaMenu = (menu) => {
    megaMenu.value = menu;
  };

  /**
   * Close mega menu
   */
  const closeMegaMenu = () => {
    megaMenu.value = null;
  };

  /**
   * Toggle expert popover
   */
  const toggleExpertPopover = () => {
    expertPopoverOpen.value = !expertPopoverOpen.value;
  };

  /**
   * Open expert popover
   */
  const openExpertPopover = () => {
    expertPopoverOpen.value = true;
  };

  /**
   * Close expert popover
   */
  const closeExpertPopover = () => {
    expertPopoverOpen.value = false;
  };

  /**
   * Close all navigation menus
   */
  const closeAll = () => {
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

