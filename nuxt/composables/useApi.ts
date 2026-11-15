export function useApi() {
  const config = useRuntimeConfig()
  const token = useCookie<string | null>('vc_token', { sameSite: 'lax' })

  async function api<T>(path: string, opts: Record<string, any> = {}): Promise<T> {
    const headers: Record<string, string> = {
      Accept: 'application/json',
      ...(opts.headers || {}),
    }
    if (token.value) {
      headers.Authorization = `Bearer ${token.value}`
    }
    return await $fetch<T>(path.startsWith('http') ? path : `/api/v1${path}`, {
      ...opts,
      headers,
      credentials: 'include',
      baseURL: opts.baseURL,
    })
  }

  function setToken(value: string | null) {
    token.value = value
  }

  return { api, token, setToken, config }
}
