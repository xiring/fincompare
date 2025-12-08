declare module 'quill' {
    export default class Quill {
      constructor(container: string | Element, options?: any);
      clipboard: {
        dangerouslyPasteHTML(html: string): void;
      };
      getModule(name: string): any;
      getSelection(focus?: boolean): { index: number; length: number } | null;
      insertEmbed(index: number, type: string, value: any): void;
      on(event: string, handler: () => void): void;
    }
  }
