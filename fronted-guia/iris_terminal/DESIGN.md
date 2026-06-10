---
name: Iris Terminal
colors:
  surface: '#131315'
  surface-dim: '#131315'
  surface-bright: '#39393b'
  surface-container-lowest: '#0e0e10'
  surface-container-low: '#1b1b1d'
  surface-container: '#1f1f21'
  surface-container-high: '#2a2a2c'
  surface-container-highest: '#353437'
  on-surface: '#e4e2e4'
  on-surface-variant: '#bbc9cd'
  inverse-surface: '#e4e2e4'
  inverse-on-surface: '#303032'
  outline: '#859397'
  outline-variant: '#3c494c'
  surface-tint: '#2fd9f4'
  primary: '#8aebff'
  on-primary: '#00363e'
  primary-container: '#22d3ee'
  on-primary-container: '#005763'
  inverse-primary: '#006877'
  secondary: '#c0c7d3'
  on-secondary: '#2a313b'
  secondary-container: '#404752'
  on-secondary-container: '#afb5c2'
  tertiary: '#ffd6a3'
  on-tertiary: '#462b00'
  tertiary-container: '#ffb13b'
  on-tertiary-container: '#6e4600'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#a2eeff'
  primary-fixed-dim: '#2fd9f4'
  on-primary-fixed: '#001f25'
  on-primary-fixed-variant: '#004e5a'
  secondary-fixed: '#dce3f0'
  secondary-fixed-dim: '#c0c7d3'
  on-secondary-fixed: '#151c25'
  on-secondary-fixed-variant: '#404752'
  tertiary-fixed: '#ffddb5'
  tertiary-fixed-dim: '#ffb957'
  on-tertiary-fixed: '#2a1800'
  on-tertiary-fixed-variant: '#643f00'
  background: '#131315'
  on-background: '#e4e2e4'
  surface-variant: '#353437'
typography:
  display:
    fontFamily: Geist
    fontSize: 36px
    fontWeight: '700'
    lineHeight: 44px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Geist
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Geist
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Geist
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Geist
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: JetBrains Mono
    fontSize: 10px
    fontWeight: '500'
    lineHeight: 14px
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 4px
  container-padding: 24px
  gutter: 16px
  sidebar-width: 260px
---

## Brand & Style
The design system for Iris Computer is built upon a **Technical Minimalism** aesthetic, optimized for high-intensity ERP workflows and component inventory management. The brand personality is precise, authoritative, and high-performance, catering to technical professionals who require clarity in complex data environments.

The visual language utilizes a "Strict Dark" palette to reduce eye strain during prolonged use, punctuated by high-visibility cyan accents that draw attention to primary actions and system statuses. Drawing from **modern developer-tool aesthetics**, the system emphasizes structural integrity through subtle borders and depth created via tonal layering rather than heavy shadows. The emotional response is one of controlled efficiency—a digital workbench that feels as advanced as the hardware it tracks.

## Colors
This design system operates on a rigorous dark-mode foundation.

- **Primary Background**: `#161618` serves as the base layer, providing deep contrast for content.
- **Surface/Elevated**: `#202022` is used for sidebar, cards, and navigation headers to create structural hierarchy.
- **Accents**: Cyan-400 (`#22D3EE`) is the singular brand identifier, reserved for primary CTA buttons, active navigation states, and progress indicators.
- **Semantic Statuses**:
  - **Success**: Emerald-400 text on 10% opacity background.
  - **Error**: Red-400 text on 10% opacity background.
  - **Warning**: Orange-400 text on 10% opacity background.
- **Borders**: Defined by `#1F2937` (gray-800) for subtle separation and `#374151` (gray-700) for interactive element strokes.

## Typography
The typography strategy leverages **Geist** for its neutral, technical clarity and high readability in data-heavy interfaces. It is paired with **JetBrains Mono** for labels, SKU numbers, and technical specs to reinforce the "component sales" identity.

- **Headlines**: Use Gray-100 (`#F3F4F6`) for maximum prominence.
- **Body Text**: Use Gray-100 for primary reading and Gray-400 (`#9CA3AF`) for supporting information and metadata.
- **Monospace Accents**: All numerical data and status labels utilize JetBrains Mono to ensure character alignment in tables and lists.

## Layout & Spacing
The design system employs a **Fixed-Fluid Hybrid** layout. 

1. **Sidebar**: A fixed 260px sidebar (`#202022`) houses the primary navigation.
2. **Main Canvas**: A fluid content area with a max-width of 1600px for ultra-wide monitors, ensuring data tables don't become illegible.
3. **Grid**: A 12-column grid system is used for dashboard widgets.
4. **Rhythm**: An 8px linear scaling system governs all padding and margins. 

**Breakpoints**:
- **Mobile (<768px)**: Sidebar collapses into a bottom-sheet or hamburger menu. Margins reduce to 16px.
- **Tablet (768px - 1024px)**: Sidebar collapses to icons-only (rail). 2-column grid for cards.
- **Desktop (>1024px)**: Full sidebar. 3 or 4-column grid for metrics and cards.

## Elevation & Depth
Depth is communicated through **Tonal Layering** and **Luminous Accents** rather than physical shadows.

- **Level 0 (Base)**: `#161618` - The application canvas.
- **Level 1 (Surface)**: `#202022` - Cards, Sidebar, and Top Navigation. Borders are `#1F2937`.
- **Level 2 (Interaction)**: Active states and dropdowns. These use a slightly lighter background or a `#374151` border.
- **Glow Effect**: Primary buttons and active indicators utilize a `0px 0px 15px rgba(34, 211, 238, 0.3)` box-shadow to simulate a neon hardware glow, suggesting the element is "powered on."

## Shapes
The shape language is "Soft-Technical." Elements use a **0.25rem (4px)** base radius to maintain a professional, organized appearance that avoids the playfulness of fully rounded corners.

- **Small Components**: Inputs, checkboxes, and tags use 4px.
- **Large Components**: Cards and modals use **8px (rounded-lg)**.
- **Buttons**: Use 4px to maintain a sharp, functional look.

## Components

### Buttons
- **Primary**: Background Cyan-400, Text Black (`#000`), with Cyan neon glow on hover.
- **Secondary**: Ghost style. Border Gray-700, Text Gray-100. Hover: Border Cyan-400/50.

### Status Badges
Used for order status (Pending, Shipped, Cancelled).
- **Structure**: 1px border at 20% opacity, 10% opacity background fill, solid color text.
- **Font**: JetBrains Mono, Uppercase, 10px.

### Input Fields
- **Default**: Background `#161618`, Border Gray-800, Text Gray-100.
- **Focus**: Border Cyan-400, Ring Cyan-400 (0.5px).
- **Placeholder**: Gray-500.

### Cards
- **Container**: Background `#202022`, 1px border Gray-800.
- **Header**: Subtle bottom border Gray-800 to separate title from content.

### Data Tables
- **Header**: Gray-900 background, Gray-400 uppercase text (JetBrains Mono).
- **Rows**: Hover state changes background to `#262629`. Border-bottom Gray-800.

### Interactivity (Alpine.js)
- **Transitions**: Use `transition-all duration-200 ease-in-out` for all hover states.
- **Modals**: Backdrop blur (8px) with a dark overlay (`rgba(0,0,0,0.6)`).