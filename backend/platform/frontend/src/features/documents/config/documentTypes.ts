export type DocumentType =
  | 'certificate'
  | 'diploma'
  | 'badge'
  | 'id_card'
  | 'training_card'
  | 'custom'

export type DocumentLanguage =
  | 'en'
  | 'fr'
  | 'en-fr'

export interface DocumentTypeOption {
  value: DocumentType
  label: string
}

export interface LanguageOption {
  value: DocumentLanguage
  label: string
}

export const DOCUMENT_TYPES:
  DocumentTypeOption[] = [
    {
      value:
        'certificate',

      label:
        'Certificate',
    },

    {
      value:
        'diploma',

      label:
        'Diploma',
    },

    {
      value:
        'badge',

      label:
        'Badge',
    },

    {
      value:
        'id_card',

      label:
        'ID Card',
    },

    {
      value:
        'training_card',

      label:
        'Training Card',
    },

    {
      value:
        'custom',

      label:
        'Custom Document',
    },
  ]

export const DOCUMENT_LANGUAGES:
  LanguageOption[] = [
    {
      value:
        'en',

      label:
        'English',
    },

    {
      value:
        'fr',

      label:
        'Français',
    },

    {
      value:
        'en-fr',

      label:
        'English / Français',
    },
  ]

export function defaultPaperForDocumentType(
  type: DocumentType,
): {
  paperSize: string
  orientation:
    'portrait' |
    'landscape'
} {
  switch (type) {
    case 'certificate':
      return {
        paperSize:
          'a4',

        orientation:
          'landscape',
      }

    case 'diploma':
      return {
        paperSize:
          'a4',

        orientation:
          'portrait',
      }

    case 'badge':
      return {
        paperSize:
          'badge-square',

        orientation:
          'portrait',
      }

    case 'id_card':
    case 'training_card':
      return {
        paperSize:
          'cr80',

        orientation:
          'landscape',
      }

    case 'custom':
    default:
      return {
        paperSize:
          'a4',

        orientation:
          'landscape',
      }
  }
}

export function documentHasBackSide(
  type: DocumentType,
): boolean {
  return (
    type === 'id_card' ||
    type === 'training_card'
  )
}