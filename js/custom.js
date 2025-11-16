document.addEventListener('DOMContentLoaded', function () {

  // Constants for timing
  const DOUBLE_TAP_THRESHOLD = 300; // milliseconds
  const TOUCH_ACTIVE_TIMEOUT = 3000; // milliseconds
  const SCROLL_THRESHOLD = 500; // pixels

  // Scroll to top button
  const scrollBtn = document.querySelector('.scrolltotop');

  if (scrollBtn) {
    scrollBtn.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', function () {
      if (window.scrollY > SCROLL_THRESHOLD) {
        scrollBtn.style.display = 'block';
        scrollBtn.style.opacity = '1';
      } else {
        scrollBtn.style.opacity = '0';
        setTimeout(() => {
          scrollBtn.style.display = 'none';
        }, 300);
      }
    });
  }
  

 //=======Mobile Sidebar Menu (Bootstrap-style)
    const menuBtn = document.querySelector('.menu-btn');
    const sidebarMenu = document.querySelector('.sidebar-menu');
    const closeBtns = document.querySelectorAll('.close-icon');
    const overlay = document.querySelector('.overlay');

    // Check if mobile menu elements exist before adding event listeners
    if (!menuBtn || !sidebarMenu || !overlay) {
      console.warn('Mobile menu elements not found on this page');
      return; // Exit if mobile menu doesn't exist
    }

    // Helper function for haptic feedback
    function vibrate(duration = 10) {
      if (navigator.vibrate) {
        navigator.vibrate(duration);
      }
    }

    // Function to close all submenus
    function closeAllSubmenus() {
      const allSubmenus = document.querySelectorAll('.sub-menu');
      const allSubBtns = document.querySelectorAll('.sub-btn');
      
      allSubmenus.forEach(submenu => {
        submenu.style.maxHeight = null;
      });
      
      allSubBtns.forEach(btn => {
        btn.classList.remove('submenu-open');
      });
    }

    // Function to clear all touched states
    function clearTouchedStates() {
      closeAllSubmenus();
    }

    // Open menu
    menuBtn.addEventListener('click', function () {
      sidebarMenu.classList.add('active');
      overlay.classList.add('active');
      
      // Accessibility: Set ARIA attributes
      menuBtn.setAttribute('aria-expanded', 'true');
      sidebarMenu.setAttribute('aria-hidden', 'false');
      
      // Focus management - focus first menu item
      const firstMenuItem = sidebarMenu.querySelector('.menu-item a');
      if (firstMenuItem) {
        setTimeout(() => firstMenuItem.focus(), 300);
      }
      
      vibrate(10);
    });

    // Close menu function
    function closeMenu() {
      sidebarMenu.classList.remove('active');
      overlay.classList.remove('active');
      
      // Accessibility: Update ARIA attributes
      menuBtn.setAttribute('aria-expanded', 'false');
      sidebarMenu.setAttribute('aria-hidden', 'true');
      
      // Return focus to menu button
      menuBtn.focus();
      
      // Close all submenus
      clearTouchedStates();
      
      vibrate(10);
    }

    // Close button
    closeBtns.forEach(btn => {
      btn.addEventListener('click', closeMenu);
    });

    // Overlay click
    overlay.addEventListener('click', closeMenu);

    // Keyboard support - close menu with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && sidebarMenu.classList.contains('active')) {
        closeMenu();
      }
    });

    //=======Submenu functionality (Bootstrap collapse-style)
    const subMenuButtons = document.querySelectorAll('.sub-btn');

    subMenuButtons.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        const subMenu = this.nextElementSibling;
        
        // Check if this element has a submenu
        if (!subMenu || !subMenu.classList.contains('sub-menu')) {
          // No submenu, allow navigation
          return;
        }

        // Prevent default navigation for parent items with submenus
        e.preventDefault();
        e.stopPropagation();

        // Check if this submenu is currently open
        const isOpen = subMenu.style.maxHeight && subMenu.style.maxHeight !== '0px';

        // Close all other submenus (accordion style)
        const allSubmenus = document.querySelectorAll('.sub-menu');
        const allSubBtns = document.querySelectorAll('.sub-btn');
        
        allSubmenus.forEach(sm => {
          if (sm !== subMenu) {
            sm.style.maxHeight = null;
          }
        });
        
        allSubBtns.forEach(sb => {
          if (sb !== this) {
            sb.classList.remove('submenu-open');
          }
        });

        // Toggle current submenu
        if (isOpen) {
          // Close this submenu
          subMenu.style.maxHeight = null;
          this.classList.remove('submenu-open');
          vibrate(10);
        } else {
          // Open this submenu
          subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
          this.classList.add('submenu-open');
          vibrate(15);
          
          // Update maxHeight on window resize
          const updateHeight = () => {
            if (subMenu.style.maxHeight) {
              subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
            }
          };
          window.addEventListener('resize', updateHeight);
        }

        // Update ARIA attributes for accessibility
        this.setAttribute('aria-expanded', !isOpen);
      });
    });

  //======= Job Application File Upload Functionality
  const fileInput = document.getElementById('file-cv');
  const pdfWrap = document.querySelector('.pdf-wrap');
  const fileUploadDiv = document.querySelector('.file-uplode');
  const pdfUploadDiv = document.querySelector('.pdf-upload');
  const uploadedFilename = document.querySelector('.uploaded-filename');
  const removeFileBtn = document.querySelector('.remove-file');
  const fileCount = document.querySelector('.file-count');
  const browseLink = document.querySelector('.file-uplode a');

  if (fileInput && pdfWrap && fileUploadDiv && pdfUploadDiv) {
    
    // Make the "Browse" link trigger file input
    if (browseLink) {
      browseLink.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.click();
      });
    }

    // Make the entire drop area clickable
    pdfWrap.addEventListener('click', function(e) {
      // Don't trigger if clicking the browse link (it has its own handler)
      if (!e.target.closest('a')) {
        fileInput.click();
      }
    });

    // Handle file selection
    fileInput.addEventListener('change', function() {
      if (this.files && this.files.length > 0) {
        const file = this.files[0];
        const fileName = file.name;
        const fileSize = file.size;
        const maxSize = 5 * 1024 * 1024; // 5MB

        // Validate file size
        if (fileSize > maxSize) {
          alert('File size exceeds 5MB. Please upload a smaller file.');
          this.value = ''; // Clear the input
          return;
        }

        // Validate file type
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
          alert('Invalid file type. Please upload PDF, DOC, or DOCX files only.');
          this.value = ''; // Clear the input
          return;
        }

        // Update UI to show uploaded file
        uploadedFilename.textContent = fileName;
        fileCount.textContent = '1';
        
        // Hide drop area, show uploaded file info
        fileUploadDiv.style.display = 'none';
        pdfUploadDiv.style.display = 'block';
      }
    });

    // Handle drag and drop
    pdfWrap.addEventListener('dragover', function(e) {
      e.preventDefault();
      e.stopPropagation();
      this.style.borderColor = '#0066cc';
      this.style.backgroundColor = '#f0f8ff';
    });

    pdfWrap.addEventListener('dragleave', function(e) {
      e.preventDefault();
      e.stopPropagation();
      this.style.borderColor = '';
      this.style.backgroundColor = '';
    });

    pdfWrap.addEventListener('drop', function(e) {
      e.preventDefault();
      e.stopPropagation();
      this.style.borderColor = '';
      this.style.backgroundColor = '';

      const files = e.dataTransfer.files;
      if (files.length > 0) {
        // Set the file to the input
        fileInput.files = files;
        // Trigger change event
        fileInput.dispatchEvent(new Event('change'));
      }
    });

    // Handle file removal
    if (removeFileBtn) {
      removeFileBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Clear the file input
        fileInput.value = '';
        
        // Reset UI
        fileUploadDiv.style.display = 'block';
        pdfUploadDiv.style.display = 'none';
        uploadedFilename.textContent = 'your-file-here.PDF';
        fileCount.textContent = '1';
      });
    }
  }

  // ======= CF7 File Upload Enhancement
  const cf7FileWraps = document.querySelectorAll('.cf7-job-application-form .pdf-wrap, .file-upload-wrapper .pdf-wrap');
  
  cf7FileWraps.forEach(function(pdfWrap) {
      // Find the file input - try multiple selectors
      let fileInput = pdfWrap.querySelector('input[type="file"]');
      if (!fileInput) {
          fileInput = document.getElementById('cv-file-input');
      }
      if (!fileInput) {
          fileInput = document.querySelector('.file-uplode input[type="file"]');
      }
      
      const fileUploadDiv = pdfWrap.querySelector('.file-uplode') || document.querySelector('.file-uplode');
      const pdfUploadDiv = pdfWrap.parentElement ? pdfWrap.parentElement.querySelector('.pdf-upload') : document.querySelector('.pdf-upload');
      const uploadedFilename = pdfUploadDiv ? pdfUploadDiv.querySelector('.uploaded-filename') : null;
      const removeFileBtn = pdfUploadDiv ? pdfUploadDiv.querySelector('.remove-file') : null;
      const browseTrigger = pdfWrap.querySelector('.browse-trigger') || document.querySelector('.browse-trigger');
      
      if (!fileInput || !fileUploadDiv) return;
      
      console.log('CF7 File upload initialized', {fileInput, fileUploadDiv, pdfUploadDiv});
      
      // Make browse trigger clickable
      if (browseTrigger) {
          browseTrigger.addEventListener('click', function(e) {
              e.preventDefault();
              e.stopPropagation();
              console.log('Browse trigger clicked');
              fileInput.click();
          });
      }
      
      // Make entire area clickable
      pdfWrap.addEventListener('click', function(e) {
          if (!e.target.closest('.browse-trigger') && !e.target.closest('.wpcf7-form-control-wrap')) {
              console.log('Upload area clicked');
              fileInput.click();
          }
      });
      
      // Handle file selection
      fileInput.addEventListener('change', function() {
          console.log('File selected', this.files);
          if (this.files && this.files.length > 0) {
              const file = this.files[0];
              const fileName = file.name;
              
              if (uploadedFilename && pdfUploadDiv) {
                  // Update UI
                  uploadedFilename.textContent = fileName;
                  fileUploadDiv.style.display = 'none';
                  pdfUploadDiv.style.display = 'block';
                  console.log('File display updated:', fileName);
              }
          }
      });
      
      // Drag and drop
      pdfWrap.addEventListener('dragover', function(e) {
          e.preventDefault();
          e.stopPropagation();
          fileUploadDiv.classList.add('dragover');
      });
      
      pdfWrap.addEventListener('dragleave', function(e) {
          e.preventDefault();
          e.stopPropagation();
          fileUploadDiv.classList.remove('dragover');
      });
      
      pdfWrap.addEventListener('drop', function(e) {
          e.preventDefault();
          e.stopPropagation();
          fileUploadDiv.classList.remove('dragover');
          
          const files = e.dataTransfer.files;
          if (files.length > 0) {
              console.log('File dropped');
              fileInput.files = files;
              fileInput.dispatchEvent(new Event('change', {bubbles: true}));
          }
      });
      
      // Remove file
      if (removeFileBtn) {
          removeFileBtn.addEventListener('click', function(e) {
              e.preventDefault();
              e.stopPropagation();
              
              console.log('File removed');
              fileInput.value = '';
              if (pdfUploadDiv) {
                  fileUploadDiv.style.display = 'block';
                  pdfUploadDiv.style.display = 'none';
              }
          });
      }
  });
  
  // Reset form display on successful CF7 submission
  document.addEventListener('wpcf7mailsent', function(event) {
      console.log('CF7 form submitted successfully');
      const form = event.target;
      const pdfUploadDiv = form.querySelector('.pdf-upload');
      const fileUploadDiv = form.querySelector('.file-uplode');
      
      if (pdfUploadDiv && fileUploadDiv) {
          fileUploadDiv.style.display = 'block';
          pdfUploadDiv.style.display = 'none';
      }
  });

// main
});
