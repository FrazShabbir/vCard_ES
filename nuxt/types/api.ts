export type AccessState = 'trialing' | 'active' | 'lapsed'

export interface ApiUser {
  id: number
  first_name: string
  last_name: string
  full_name: string
  email: string
  username: string
  phone?: string | null
  type: 'personal' | 'business'
  email_verified_at?: string | null
  roles?: string[]
}

export interface AccessPayload {
  access: AccessState
  entitled: boolean
  email_verified: boolean
  subscription?: {
    id: number
    status: string
    trial_ends_at?: string | null
    current_period_end?: string | null
    plan?: {
      id: number
      key: string
      name: string
      profile_type: string
      months: number
      price_paisa: number
      price_label: string
    } | null
  } | null
}

export interface PlanDto {
  id: number
  key: string
  name: string
  profile_type: string
  months: number
  price_paisa: number
  price_label: string
  per_month_label: string
  badge?: string | null
}
